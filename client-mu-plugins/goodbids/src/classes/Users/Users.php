<?php
/**
 * User-specific Methods
 *
 * @package GoodBids
 * @since 1.0.0
 */

namespace GoodBids\Users;

use GoodBids\Auctions\Bids;
use GoodBids\Auctions\FreeBid;
use GoodBids\Plugins\WooCommerce\Coupons;
use GoodBids\Utilities\Log;
use WP_User;

/**
 * User Class
 *
 * @since 1.0.0
 */
class Users {

	/**
	 * @since 1.0.0
	 */
	const FREE_BID_NONCE_ACTION = 'admin_grant_free_bid';

	/**
	 * @since 1.0.0
	 */
	const FREE_BID_REASON_FIELD = 'free_bid_reason';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Add UI for Super Admins to grant free bids to users.
		$this->free_bid_user_fields();

		// Handle Free Bid via AJAX.
		$this->grant_free_bid_ajax();

		// Set up JS Vars.
		$this->free_bid_js_vars();
	}

	/**
	 * Get an array of all free bids for a User, filterable by status.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 * @param string $status
	 *
	 * @return FreeBid[]
	 */
	public function get_free_bids( ?int $user_id = null, string $status = Bids::FREE_BID_STATUS_ALL ): array {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		/** @var FreeBid[] $free_bids */
		$free_bids = get_user_meta( $user_id, Bids::FREE_BIDS_META_KEY, true );

		if ( ! $free_bids || ! is_array( $free_bids ) ) {
			return [];
		}

		return collect( $free_bids )
			->filter(
				fn ( $free_bid ) => (
					// When status is Bids::FREE_BID_STATUS_ALL, always returns true.
					Bids::FREE_BID_STATUS_ALL === $status
					// Otherwise bid must match status.
					|| $status === $free_bid->get_status()
				)
			)
			->values()
			->all();
	}

	/**
	 * Get total available free bids for a user.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return int
	 */
	public function get_available_free_bid_count( ?int $user_id = null ): int {
		return count( $this->get_free_bids( $user_id, Bids::FREE_BID_STATUS_UNUSED ) );
	}

	/**
	 * Award a Free Bid to a User
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 * @param ?int $auction_id
	 * @param string $type
	 * @param string $details
	 *
	 * @return bool
	 */
	public function award_free_bid( int $user_id, ?int $auction_id = null, string $type = FreeBid::TYPE_PAID_BID, string $details = '' ): bool {
		$free_bid = new FreeBid();

		if ( $auction_id ) {
			$free_bid->set_auction_id( $auction_id );
		}

		$free_bid->set_type( $type );
		$free_bid->set_details( $details );

		$free_bids   = $this->get_free_bids( $user_id );
		$free_bids[] = $free_bid;
		return $this->save_free_bids( $user_id, $free_bids );
	}

	/**
	 * Save Free Bids array to User Meta
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 * @param FreeBid[] $free_bids
	 *
	 * @return bool
	 */
	private function save_free_bids( int $user_id, array $free_bids ): bool {
		$original = get_user_meta( $user_id, Bids::FREE_BIDS_META_KEY, true );

		if ( $original === $free_bids ) {
			// Data is unchanged.
			return false;
		}

		return boolval( update_user_meta( $user_id, Bids::FREE_BIDS_META_KEY, $free_bids ) );
	}

	/**
	 * Redeem a Free Bid
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $order_id
	 * @param ?int $user_id
	 *
	 * @return bool
	 */
	public function redeem_free_bid( int $auction_id, int $order_id, ?int $user_id = null ): bool {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$all_free_bids    = $this->get_free_bids( $user_id );
		$unused_free_bids = $this->get_free_bids( $user_id, Bids::FREE_BID_STATUS_UNUSED );

		if ( ! count( $unused_free_bids ) ) {
			Log::error( 'No available Free Bids to redeem', compact( 'user_id', 'auction_id', 'order_id' ) );
			return false;
		}

		$redeemed = false;

		// Use the first available free bid.
		foreach ( $all_free_bids as $free_bid ) {
			if ( Bids::FREE_BID_STATUS_UNUSED !== $free_bid->get_status() ) {
				continue;
			}

			if ( $free_bid->redeem( $auction_id, $order_id ) ) {
				$redeemed = true;
				break;
			}
		}

		if ( ! $redeemed ) {
			Log::error( 'There was a problem redeeming the free bid.', compact( 'user_id', 'auction_id', 'order_id' ) );
			return false;
		}

		// Clear Cached Free Bid.
		delete_user_meta( $user_id, sprintf( Coupons::FREE_BID_COUPON_META_KEY, $auction_id ) );

		return $this->save_free_bids( $user_id, $all_free_bids );
	}

	/**
	 * Get User Email Addresses. If no user_id is provided, the current user is used.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return array
	 */
	public function get_emails( int $user_id = null ): array {
		$emails = [];

		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$user = get_user_by( 'id', $user_id );

		if ( ! $user ) {
			return $emails;
		}

		$emails[] = $user->user_email;

		$billing_email = get_user_meta( $user_id, 'billing_email', true );
		if ( $billing_email ) {
			$emails[] = $billing_email;
		}

		return $emails;
	}

	/**
	 * Display the Grant Free Bid Admin UI
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function free_bid_user_fields(): void {
		$profile_fields = function ( WP_User $user ): void {
			if ( is_user_admin() || get_current_user_id() === $user->ID ) {
				return;
			}

			$user_id = $user->ID;

			goodbids()->load_view( 'admin/users/grant-free-bid.php', compact( 'user_id' ) );
		};

		add_action( 'show_user_profile', $profile_fields, 1 );
		add_action( 'edit_user_profile', $profile_fields, 1 );
	}

	/**
	 * Set up JS Vars
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function free_bid_js_vars(): void {
		add_action(
			'goodbids_enqueue_admin_scripts',
			function ( $handle ) {
				wp_localize_script(
					$handle,
					'goodbidsFreeBids',
					[
						'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
						'reasonFieldId'   => self::FREE_BID_REASON_FIELD,
						'validationAlert' => [
							'title' => __( 'Validation Error', 'goodbids' ),
							'error' => __( 'Missing reason for granting free bid.', 'goodbids' ),
						],
						'errorAlert'      => [
							'title' => __( 'Error', 'goodbids' ),
						],
						'confirmedAlert'  => [
							'title' => __( 'Success!', 'goodbids' ),
							'text'  => __( 'The free bid was successfully granted.', 'goodbids' ),
						],
						'grantAction'     => 'goodbids_admin_grant_free_bid',
						'nonceGrant'      => wp_create_nonce( self::FREE_BID_NONCE_ACTION ),
					]
				);
			}
		);
	}

	/**
	 * Handle the AJAX action to grant the free bid.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function grant_free_bid_ajax(): void {
		add_action(
			'wp_ajax_goodbids_admin_grant_free_bid',
			function () {
				list( $user_id, $reason ) = $this->ajax_request_validation();

				if ( $user_id === get_current_user_id() ) {
					wp_send_json_error(
						[
							'error' => __( 'You are not allowed to grant free bids to yourself.', 'goodbids' ),
						],
						200
					);
					wp_die();
				}

				if ( ! $reason ) {
					wp_send_json_error(
						[
							'error' => __( 'Missing reason for granting free bid.', 'goodbids' ),
						],
						200
					);
					wp_die();
				}

				if ( ! $this->award_free_bid( $user_id, null, FreeBid::TYPE_ADMIN_GRANT, $reason ) ) {
					wp_send_json_error(
						[
							'error' => __( 'There was a problem granting the free bid.', 'goodbids' ),
						],
						200
					);
					wp_die();
				}

				wp_send_json_success( [ 'done' ] );
			}
		);
	}

	/**
	 * Validate Free Bid Grant Nonce and return user_id and details
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function ajax_request_validation(): array {
		check_ajax_referer( self::FREE_BID_NONCE_ACTION, 'nonce' );

		$data    = wp_unslash( $_POST );
		$user_id = intvaL( sanitize_text_field( $data['user_id'] ) );
		$reason  = sanitize_text_field( $data['reason'] );

		if ( ! $user_id || ! ( new WP_User( $user_id ) ) ) {
			wp_send_json_error(
				[
					'error' => __( 'Invalid data', 'goodbids' ),
				],
				200
			);
			wp_die();
		}

		return [ $user_id, $reason ];
	}
}
