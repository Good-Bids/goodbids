<?php
/**
 * WooCommerce Account Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use GoodBids\Auctions\Bids;
use GoodBids\Auctions\Rewards;
use GoodBids\Plugins\WooCommerce;
use GoodBids\Users\Referrals\Referrer;

/**
 * Class for Account Methods
 *
 * @since 1.0.0
 */
class Account {

	/**
	 * @since 1.0.0
	 */
	const BIDS_SLUG = 'bids';

	/**
	 * @since 1.0.0
	 */
	const AUCTIONS_SLUG = 'my-auctions';

	/**
	 * @since 1.0.0
	 */
	const FREE_BIDS_SLUG = 'free-bids';

	/**
	 * @since 1.0.0
	 */
	const REWARDS_SLUG = 'my-rewards';

	/**
	 * @since 1.0.0
	 */
	const REFERRALS_SLUG = 'my-referrals';

	/**
	 * Initialize Account
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		/**
		 * Note: Tabs will be added in reverse order.
		 */

		// 5. Custom My Account > Referrals page.
		$this->add_referrals_tab();

		// 4. Custom My Account > Rewards page.
		$this->add_rewards_tab();

		// 3. Custom My Account > Free Bids page.
		$this->add_free_bids_tab();

		// 2. Custom My Account > Auctions page.
		$this->add_auctions_tab();

		// 1. Custom My Account > Bids page.
		$this->add_bids_tab();

		// 0. Remove "Orders" tab
		$this->remove_orders_tab();

		// Remove "Order Again" button
		$this->remove_order_again_button();

		// Remove "Bid Instance" meta from order view
		$this->remove_bid_instance_from_order();

		// Remove Download Link
		$this->remove_download_link();
	}

	/**
	 * Create a new Bids tab on My Account page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_bids_tab(): void {
		add_filter(
			'goodbids_account_' . self::BIDS_SLUG . '_args',
			function ( $args ) {
				$bid_orders   = goodbids()->sites->get_user_bid_orders();
				$total_orders = count( $bid_orders );
				$current_page = max( intval( get_query_var( self::BIDS_SLUG ) ), 1 );

				// Pagination
				$max_per_page = goodbids()->get_config( 'woocommerce.account.default-orders-per-page' );
				$max_pages    = ceil( $total_orders / $max_per_page );
				$offset       = ( $current_page - 1 ) * $max_per_page;
				$bid_orders   = array_slice( $bid_orders, $offset, $max_per_page );

				$args['bid_orders']   = $bid_orders;
				$args['current_page'] = $current_page;
				$args['has_orders']   = 0 < $total_orders;
				$args['total_orders'] = $total_orders;
				$args['max_pages']    = $max_pages;

				return $args;
			}
		);

		$this->init_new_account_page( self::BIDS_SLUG, __( 'Bids', 'goodbids' ) );
	}

	/**
	 * Create a new Auctions tab on My Account page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_auctions_tab(): void {
		add_filter(
			'goodbids_account_' . self::AUCTIONS_SLUG . '_args',
			function ( $args ) {
				$args['auctions'] = goodbids()->sites->get_user_participating_auctions();
				return $args;
			}
		);

		$this->init_new_account_page( self::AUCTIONS_SLUG, __( 'Auctions', 'goodbids' ) );
	}

	/**
	 * Create a new Free Bids tab on My Account page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_free_bids_tab(): void {
		add_filter(
			'goodbids_account_' . self::FREE_BIDS_SLUG . '_args',
			function ( $args ) {
				$args['free_bids'] = goodbids()->free_bids->get();
				return $args;
			}
		);

		$this->init_new_account_page( self::FREE_BIDS_SLUG, __( 'Free Bids', 'goodbids' ) );
	}

	/**
	 * Create a new Rewards tab on My Account page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_rewards_tab(): void {
		add_filter(
			'goodbids_account_' . self::REWARDS_SLUG . '_args',
			function ( $args ) {
				$reward_orders = goodbids()->sites->get_user_reward_orders();
				$total_orders  = count( $reward_orders );
				$current_page = max( intval( get_query_var( self::REWARDS_SLUG ) ), 1 );

				// Pagination
				$max_per_page  = goodbids()->get_config( 'woocommerce.account.default-orders-per-page' );
				$max_pages     = ceil( $total_orders / $max_per_page );
				$offset        = ( $current_page - 1 ) * $max_per_page;
				$reward_orders = array_slice( $reward_orders, $offset, $max_per_page );

				$args['reward_orders'] = $reward_orders;
				$args['current_page']  = $current_page;
				$args['has_orders']    = 0 < $total_orders;
				$args['total_orders']  = $total_orders;
				$args['max_pages']     = $max_pages;

				return $args;
			}
		);

		$this->init_new_account_page( self::REWARDS_SLUG, __( 'Rewards', 'goodbids' ) );
	}

	/**
	 * Create a new Referrals tab on My Account page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_referrals_tab(): void {
		add_filter(
			'goodbids_account_' . self::REFERRALS_SLUG . '_args',
			function ( $args ) {
				$referrer = new Referrer();

				$args['referrals'] = $referrer->get_referrals();

				return $args;
			}
		);

		$this->init_new_account_page( self::REFERRALS_SLUG, __( 'Referrals', 'goodbids' ) );
	}

	/**
	 * Initialize a new page on My Account
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug
	 * @param string $label
	 * @param bool   $core_wc
	 *
	 * @return void
	 */
	private function init_new_account_page( string $slug, string $label, bool $core_wc = false ): void {
		add_action(
			'init',
			fn () => add_rewrite_endpoint( $slug, EP_ROOT | EP_PAGES )
		);

		add_filter(
			'query_vars',
			function ( $vars ) use ( $slug ): array {
				if ( ! in_array( $slug, $vars, true ) ) {
					$vars[] = $slug;
				}
				return $vars;
			}
		);

		add_filter(
			'woocommerce_account_menu_items',
			function ( $items ) use ( $slug, $label ): array {
				if ( array_key_exists( $slug, $items ) ) {
					return $items;
				}

				$new_items = [];

				// Insert New Items after Orders.
				foreach ( $items as $key => $value ) {
					$new_items[ $key ] = $value;

					if ( 'dashboard' === $key ) {
						$new_items[ $slug ] = $label;
					}
				}

				return $new_items;
			}
		);

		add_action(
			'woocommerce_account_' . $slug . '_endpoint',
			function () use ( $slug, $core_wc ): void {
				$template = 'myaccount/' . $slug . '.php';
				$args     = apply_filters( 'goodbids_account_' . $slug . '_args', [] );

				if ( $core_wc ) {
					wc_get_template( $template, $args );
					return;
				}

				goodbids()->load_view( 'woocommerce/' . $template, $args );
			}
		);
	}

	/**
	 * Remove the Orders tab in lieu of custom Bids tab.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function remove_orders_tab(): void {
		add_filter(
			'woocommerce_account_menu_items',
			function ( $items ) {
				unset( $items['orders'] );
				return $items;
			}
		);
	}

	/**
	 * Returns an array of orders for a specific user.
	 * The query can be altered by overriding values in the $query_args parameter.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int  $user_id
	 * @param array $query_args
	 *
	 * @return array
	 */
	public function get_user_order_ids( ?int $user_id = null, array $query_args = [] ): array {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		$limit = ! empty( $query_args['limit'] ) ? $query_args['limit'] : -1;
		unset( $query_args['limit'] );

		$args = [
			'limit'    => $limit,
			'return'   => 'ids',
			'orderby'  => 'date',
			'order'    => 'DESC',
			'customer' => $user_id,
			'paginate' => false,
		];

		return wc_get_orders( array_merge_recursive( $args, $query_args ) );
	}

	/**
	 * Get all User Bid Order IDs
	 *
	 * @since 1.0.0
	 *
	 * @param ?int  $user_id
	 * @param int   $limit
	 * @param array $status
	 *
	 * @return int[]
	 */
	public function get_user_bid_order_ids( ?int $user_id = null, int $limit = -1, array $status = [] ): array {
		$args = [
			'meta_query' => [
				[
					'key'     => WooCommerce::TYPE_META_KEY,
					'compare' => '=',
					'value'   => Bids::ITEM_TYPE,
				],
			],
		];

		if ( -1 !== $limit ) {
			$args['limit'] = $limit;
		}

		if ( ! empty( $status ) ) {
			$args['status'] = $status;
		}

		return $this->get_user_order_ids( $user_id, $args );
	}

	/**
	 * Get all User Reward Order IDs
	 *
	 * @since 1.0.0
	 *
	 * @param ?int  $user_id
	 * @param int   $limit
	 * @param array $status
	 *
	 * @return int[]
	 */
	public function get_user_reward_order_ids( ?int $user_id = null, int $limit = -1, array $status = [] ): array {
		$args = [
			'meta_query' => [
				[
					'key'     => WooCommerce::TYPE_META_KEY,
					'compare' => '=',
					'value'   => Rewards::ITEM_TYPE,
				],
			],
		];

		if ( -1 !== $limit ) {
			$args['limit'] = $limit;
		}

		if ( ! empty( $status ) ) {
			$args['status'] = $status;
		}

		return $this->get_user_order_ids( $user_id, $args );
	}

	/**
	 * Removes the "Order Again" button from the order details.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function remove_order_again_button(): void {
		remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );
	}

	/**
	 * Removes the "Bid Instance" metadata from the order details.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function remove_bid_instance_from_order(): void {
		add_filter(
			'woocommerce_order_item_get_formatted_meta_data',
			function ( array $formatted_meta ): array {
				unset( $formatted_meta['bid_instance'] );
				return $formatted_meta;
			}
		);
	}

	/**
	 * Removes the "Download" Link.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function remove_download_link(): void {
		add_filter(
			'woocommerce_account_menu_items',
			function ( $items ) {
				unset( $items['downloads'] );
				return $items;
			}
		);
	}
}
