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
		// Custom My Account pages.
		$this->add_free_bids_tab();
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
			function ( $items ) use ( $slug ): array {
				if ( array_key_exists( $slug, $items ) ) {
					return $items;
				}

				$new_items = [];
				foreach ( $items as $id => $item ) {
					$new_items[ $id ] = $item;
					if ( 'orders' === $id ) {
						$new_items[ $slug ] = __( 'Free Bids', 'goodbids' );
					}
				}

				return $new_items;
			}
		);

		add_action(
			'woocommerce_account_' . $slug . '_endpoint',
			function () use ( $slug ) {
				$free_bids = goodbids()->users->get_free_bids();
				wc_get_template( 'myaccount/' . $slug . '.php', [ 'free_bids' => $free_bids ] );
			}
		);
	}

	/**
	 * Get all User Bid Order IDs
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 * @param int  $limit
	 *
	 * @return int[]
	 */
	public function get_user_bid_order_ids( ?int $user_id = null, int $limit = -1 ): array {
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

		return wc_get_orders( $args );
	}
}
