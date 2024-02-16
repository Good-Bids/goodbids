<?php
/**
 * Auction Creation Wizard
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

/**
 * Auction Wizard Class
 *
 * @since 1.0.0
 */
class Wizard {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-auction-wizard';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init_settings();
	}

	/**
	 * Initialize custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_settings(): void {
		add_action('admin_menu', function(): void {
			// Wizard entry point
			add_menu_page(
				esc_html__( 'Auction Wizard', 'goodbids' ),
				esc_html__( 'Auction Wizard', 'goodbids' ),
				'manage_options',
				self::PAGE_SLUG,
				[ $this, 'wizard_start_page' ],
				'dashicons-money-alt',
				5
			);

			// Production Creation Page
			add_submenu_page(
				null,
				esc_html__( 'Create Product', 'goodbids' ),
				esc_html__( 'Create Product', 'goodbids' ),
				'manage_options',
				self::PAGE_SLUG . "-product",
				[ $this, 'wizard_product_page' ]
			);

			// TODO: Auction Creation Page
			add_submenu_page(
				null,
				esc_html__( 'Create Auction', 'goodbids' ),
				esc_html__( 'Create Auction', 'goodbids' ),
				'manage_options',
				self::PAGE_SLUG . "-auction",
				[ $this, 'wizard_start_page' ]
			);

			// TODO: Finish Page
			add_submenu_page(
				null,
				esc_html__( 'Finish', 'goodbids' ),
				esc_html__( 'Finish', 'goodbids' ),
				'manage_options',
				self::PAGE_SLUG . "-finish",
				[ $this, 'wizard_start_page' ]
			);
		});
	}

	/**
	 * Auction Wizard Start Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function wizard_start_page(): void {
		require GOODBIDS_PLUGIN_PATH . 'views/admin/auctions/wizard-start.php';
	}

	/**
	 * Auction Wizard Product Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function wizard_product_page(): void {
		require GOODBIDS_PLUGIN_PATH . 'views/admin/auctions/wizard-product.php';
	}


}
