<?php
/**
 * WooCommerce Account Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use GoodBids\Auctions\Bids;
use GoodBids\Plugins\WooCommerce;

/**
 * Class for Account Methods
 *
 * @since 1.0.0
 */
class Account {

	/**
	 * Initialize Account
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		/**
		 * Note: Tabs will be added in reverse order.
		 */

		// Custom My Account > Free Bids page.
		$this->add_free_bids_tab();

		// Custom My Account > Auctions page.
		$this->add_auctions_tab();
	}

	/**
	 * Create a new Free Bids tab on My Account page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_free_bids_tab(): void {
		$slug = 'free-bids';

		add_filter(
			'goodbids_account_' . $slug . '_args',
			function ( $args ) {
				$args['free_bids'] = goodbids()->users->get_free_bids();
				return $args;
			}
		);

		$this->init_new_account_page( $slug, __( 'Free Bids', 'goodbids' ) );
	}

	/**
	 * Create a new Auctions tab on My Account page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_auctions_tab(): void {
		$slug = 'auctions';

		add_filter(
			'goodbids_account_' . $slug . '_args',
			function ( $args ) {
				$args['auctions'] = goodbids()->sites->get_user_participating_auctions();
				return $args;
			}
		);

		$this->init_new_account_page( $slug, __( 'Auctions', 'goodbids' ) );
	}

	/**
	 * Initialize a new page on My Account
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug
	 * @param string $label
	 *
	 * @return void
	 */
	private function init_new_account_page( string $slug, string $label ): void {
		add_action(
			'init',
			function () use ( $slug ): void {
				add_rewrite_endpoint( $slug, EP_ROOT | EP_PAGES );
			}
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

					if ( 'orders' === $key ) {
						$new_items[ $slug ] = $label;
					}
				}

				return $new_items;
			}
		);

		add_action(
			'woocommerce_account_' . $slug . '_endpoint',
			function () use ( $slug ) {
				wc_get_template(
					'myaccount/' . $slug . '.php',
					apply_filters( 'goodbids_account_' . $slug . '_args', [] )
				);
			}
		);
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
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		$args = [
			'limit'       => $limit,
			'return'      => 'ids',
			'orderby'     => 'date',
			'order'       => 'DESC',
			'customer'    => $user_id,
			'paginate'    => false,
			'meta_query'  => [
				[
					'key'     => WooCommerce::TYPE_META_KEY,
					'compare' => '=',
					'value'   => Bids::ITEM_TYPE,
				]
			],
		];

		if ( ! empty( $status ) ) {
			$args['status'] = $status;
		}

		return wc_get_orders( $args );
	}
}
