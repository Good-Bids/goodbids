<?php
/**
 * Rewards Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use DateInterval;
use DateTimeImmutable;
use Exception;
use GoodBids\Frontend\Notices;
use GoodBids\Plugins\WooCommerce\Coupons;
use GoodBids\Utilities\Log;
use WC_Product;

/**
 * Class for Rewards
 *
 * @since 1.0.0
 */
class Rewards {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ITEM_TYPE = 'rewards';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const CLAIM_REWARD_SLUG = 'claim-reward';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const REDEEMED_META_KEY = '_goodbids_reward_redeemed';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const CRON_UNCLAIMED_REMINDER_HOOK = 'goodbids_unclaimed_reward_reminder_event';

	/**
	 * Initialize Rewards
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Disable Rewards on Main Site.
		if ( is_main_site() ) {
			return;
		}

		// Init Rewards Category.
		$this->init_category();

		// Use the Claim Reward slug at the end of the Auction URL to claim reward.
		$this->add_claim_reward_url_rewrite();
		$this->handle_claim_reward_endpoint();

		// Connect Reward product to Auctions.
		$this->connect_reward_product_on_auction_save();

		// Adjust the price of the product to the Winning Bid when the Auction closes.
		$this->set_price_on_auction_end();

		// Mark Reward as redeemed after Checkout.
		$this->mark_as_redeemed();
	}

	/**
	 * Initialize Rewards Category
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_category(): void {
		add_action(
			'init',
			function () {
				$this->get_category_id();
			}
		);
	}

	/**
	 * Add custom rewrite rule to claim reward for Auction
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_claim_reward_url_rewrite(): void {
		add_filter(
			'query_vars',
			function ( $vars ) {
				$vars[] = self::CLAIM_REWARD_SLUG;
				return $vars;
			}
		);

		add_action(
			'init',
			function () {
				$auction_slug = Auctions::SINGULAR_SLUG;
				$post_type    = goodbids()->auctions->get_post_type();
				$claim_slug   = self::CLAIM_REWARD_SLUG;

				add_rewrite_rule(
					'^' . $auction_slug . '/([^/]+)/' . $claim_slug . '/?',
					'index.php?post_type=' . $post_type . '&name=$matches[1]&' . $claim_slug . '=1',
					'top'
				);
			}
		);
	}

	/**
	 * Redirect to the checkout page with the Reward product in cart.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function handle_claim_reward_endpoint(): void {
		add_action(
			'template_redirect',
			function (): void {
				if ( ! get_query_var( self::CLAIM_REWARD_SLUG ) ) {
					return;
				}

				nocache_headers();

				$auction_id = get_queried_object_id();
				$auction    = new Auction( $auction_id );
				$reward_id  = $auction->get_reward_id();

				if ( ! is_user_logged_in() ) {
					$auth_url    = wc_get_page_permalink( 'myaccount' );
					$redirect_to = $auction->get_claim_reward_url();

					goodbids()->notices->add_notice( Notices::NOT_AUTHENTICATED );
					$redirect = add_query_arg( 'redirect-to', urlencode( $redirect_to ), $auth_url );

					wp_safe_redirect( $redirect );
					exit;
				}

				if ( ! $auction->is_current_user_winner() ) {
					goodbids()->notices->add_notice( Notices::NOT_AUCTION_WINNER );
					wp_safe_redirect( $auction->get_url() );
					exit;
				}

				if ( ! $auction->has_ended() ) {
					goodbids()->notices->add_notice( Notices::AUCTION_NOT_ENDED );
					wp_safe_redirect( $auction->get_url() );
					exit;
				}

				// Make sure the price has been updated.
				$this->update_price_to_auction_bid( $auction_id );

				if ( $this->is_redeemed( $auction_id ) ) {
					goodbids()->notices->add_notice( Notices::REWARD_ALREADY_REDEEMED );
					wp_safe_redirect( $auction->get_url() );
					exit;
				}

				if ( ! $reward_id  ) {
					// TODO: Add Error Message
					wp_safe_redirect( $auction->get_url() );
					exit;
				}

				WC()->cart->empty_cart();

				try {
					WC()->cart->add_to_cart( $reward_id, 1 );
				} catch ( \Exception $e ) {
					Log::error( $e->getMessage() );

					// TODO: Add Error Message
					wp_safe_redirect( $auction->get_url() );
					exit();
				}

				/**
				 * Action to perform when a reward is added to cart.
				 *
				 * @since 1.0.0
				 *
				 * @param int $auction_id The Auction ID.
				 * @param int $reward_id The Reward Product ID.
				 */
				do_action( 'goodbids_claim_reward', $auction_id, $reward_id );

				/**
				 * Filter the URL to redirect after reward has been added to cart.
				 *
				 * @since 1.0.0
				 *
				 * @param string $redirect The URL to redirect to.
				 * @param int $auction_id The Auction ID.
				 */
				$redirect = apply_filters( 'goodbids_claim_reward_redirect', wc_get_checkout_url(), $auction_id );

				wp_safe_redirect( $redirect );
				exit();
			}
		);
	}

	/**
	 * Retrieve the Rewards category ID, or create it if it doesn't exist.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_category_id(): ?int {
		$rewards_category = get_term_by( 'slug', self::ITEM_TYPE, 'product_cat' );

		if ( ! $rewards_category ) {
			// Inserting the Rewards Category throws an error with HyperDB.
			goodbids()->utilities->disable_hyperdb_temporarily();

			$rewards_category = wp_insert_term( 'Rewards', 'product_cat' );

			if ( is_wp_error( $rewards_category ) ) {
				Log::error( $rewards_category->get_error_message() );
				return null;
			}

			return $rewards_category['term_id'];
		}

		return $rewards_category->term_id;
	}

	/**
	 * Update the Reward Product when an Auction is saved.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function connect_reward_product_on_auction_save(): void {
		add_action(
			'wp_after_insert_post',
			function ( int $post_id ): void {
				// Bail if not an Auction and not published.
				if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) || goodbids()->auctions->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				$reward_id = $this->get_product_id( $post_id );

				if ( ! $reward_id ) {
					return;
				}

				update_post_meta( $reward_id, Auctions::PRODUCT_AUCTION_META_KEY, $post_id );
			}
		);
	}

	/**
	 * Returns the Add to Cart URL for the Reward Product
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return string
	 */
	public function get_claim_reward_url( ?int $auction_id = null ): string {
		return trailingslashit( get_permalink( $auction_id ) ) . self::CLAIM_REWARD_SLUG . '/';
	}

	/**
	 * Get deadline date to claim reward
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $auction_id
	 * @param string $format
	 *
	 * @return ?string
	 */
	public function get_claim_deadline_date( ?int $auction_id = null, string $format = 'n/j/Y' ): ?string {
		$reward_days_to_claim = intval( goodbids()->get_config( 'auctions.reward-days-to-claim' ) );

		if ( ! $auction_id || ! $reward_days_to_claim ) {
			return null;
		}

		$auction    = goodbids()->auctions->get( $auction_id );
		$close_date = $auction->get_end_date_time( 'Y-m-d H:i:s' );

		try {
			$close    = new DateTimeImmutable( $close_date );
			$deadline = $close->add( new DateInterval( 'P' . $reward_days_to_claim . 'D' ) )->format( $format );
		} catch ( Exception $e ) {
			Log::error( 'Error getting claim deadline date.', [ 'error' => $e->getMessage() ] );
			return null;
		}

		return $deadline;
	}

	/**
	 * Get the Auction Reward Product ID.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_product_id( int $auction_id = null ): int {
		if ( ! $auction_id ) {
			$auction_id = goodbids()->auctions->get_auction_id();
		}

		$auction = goodbids()->auctions->get( $auction_id );

		return intval( $auction->get_setting( 'auction_product' ) );
	}

	/**
	 * Get the Auction Reward Product object.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return ?WC_Product
	 */
	public function get_product( int $auction_id = null ): ?WC_Product {
		$reward_product_id = $this->get_product_id( $auction_id );

		if ( ! $reward_product_id ) {
			return null;
		}

		return wc_get_product( $reward_product_id );
	}

	/**
	 * Check if reward for an Auction has been redeemed
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function is_redeemed( ?int $auction_id = null ): bool {
		$reward_id = $this->get_product_id( $auction_id );
		return boolval( get_post_meta( $reward_id, self::REDEEMED_META_KEY, true ) );
	}

	/**
	 * Get the Auction ID from the Reward Product ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $reward_product_id
	 *
	 * @return ?int
	 */
	public function get_auction_id( int $reward_product_id ): ?int {
		$auction_id = get_post_meta( $reward_product_id, Auctions::PRODUCT_AUCTION_META_KEY, true );
		return $auction_id ? intval( $auction_id ) : null;
	}

	/**
	 * Mark the reward product for an Auction as redeemed after Checkout.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function mark_as_redeemed(): void {
		add_action(
			'woocommerce_thankyou',
			function ( int $order_id ): void {
				if ( is_admin() ) {
					return;
				}

				if ( ! goodbids()->woocommerce->orders->is_reward_order( $order_id ) ) {
					return;
				}

				$auction_id = goodbids()->woocommerce->orders->get_auction_id( $order_id );
				$reward_id  = $this->get_product_id( $auction_id );

				update_post_meta( $reward_id, self::REDEEMED_META_KEY, 1 );
				delete_post_meta( $reward_id, sprintf( Coupons::REWARD_COUPON_META_KEY, $auction_id ), true );

				/**
				 * Reward Redeemed by Auction Winner
				 *
				 * @since 1.0.0
				 *
				 * @param int $auction_id
				 * @param int $order_id
				 */
				do_action( 'goodbids_reward_redeemed', $auction_id, $order_id );

				wp_safe_redirect( get_permalink( $auction_id ) );
				exit;
			}
		);
	}

	/**
	 * Adjust the price of the Reward Product to the Winning Bid when the Auction closes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_price_on_auction_end(): void {
		add_action(
			'goodbids_auction_end',
			fn ( int $auction_id ) => $this->update_price_to_auction_bid( $auction_id )
		);
	}

	/**
	 * Update the Reward product price to the last bid amount.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return void
	 */
	public function update_price_to_auction_bid( int $auction_id ): void {
		$auction = goodbids()->auctions->get( $auction_id );
		$reward  = $auction?->get_reward();

		if ( ! $reward ) {
			Log::error( 'Auction or Reward not found.', compact( 'auction_id' ) );
			return;
		}

		$winning_bid = $auction->get_last_bid();

		if ( ! $winning_bid ) {
			Log::error( 'No winning bid found.', compact( 'auction_id' ) );
			return;
		}

		if ( floatval( $reward->get_price( 'edit' ) ) === $winning_bid->get_subtotal() ) {
			return;
		}

		$reward->set_regular_price( $winning_bid->get_subtotal() );
		$reward->set_price( $winning_bid->get_subtotal() );
		$reward->save();
	}
}
