<?php
/**
 * WooCommerce Admin Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use GoodBids\Utilities\Log;
use WP_Post;
use WP_Screen;

/**
 * Class for Admin Methods
 *
 * @since 1.0.0
 */
class Admin {

	/**
	 * Initialize Admin
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Auction Admin Meta box.
		$this->add_auction_meta_box();

		// Create a custom WC Auth Page.
		$this->create_auth_page();

		// Custom WooCommerce Settings.
		$this->add_auth_page_setting();

		// Display Custom Page Post States.
		$this->display_post_states();

		// Remove WooCommerce Features on Main site
		$this->main_site_cleanup();

		// Limit access to pages
		$this->limit_access_to_pages();
	}

	/**
	 * Add an "Auction Info" meta box to Orders post types.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_auction_meta_box(): void {
		add_action(
			'current_screen',
			function ( WP_Screen $screen ): void {
				if ( 'woocommerce_page_wc-orders' !== $screen->id ) {
					return;
				}

				$order_id = ! empty( $_GET['id'] ) ? intval( sanitize_text_field( $_GET['id'] ) ) : false; // phpcs:ignore

				if ( ! $order_id || ! goodbids()->woocommerce->orders->get_auction_id( $order_id ) ) {
					return;
				}

				// Add and Render the Meta Box.
				add_meta_box(
					'goodbids-auction-info',
					__( 'Auction Info', 'goodbids' ),
					function () {
						$order_id   = ! empty( $_GET['id'] ) ? intval( sanitize_text_field( $_GET['id'] ) ) : false; // phpcs:ignore
						$auction_id = goodbids()->woocommerce->orders->get_auction_id( $order_id );

						printf(
							'<p><strong>%s</strong><br><a href="%s">%s</a> (ID: %s)</p>',
							esc_html__( 'Related Auction', 'goodbids' ),
							esc_url( get_edit_post_link( $auction_id ) ),
							esc_html( get_the_title( $auction_id ) ),
							esc_html( $auction_id )
						);

						$reward_id = goodbids()->rewards->get_product_id( $auction_id );

						printf(
							'<p><strong>%s</strong><br><a href="%s">%s</a> (ID: %s)</p>',
							esc_html__( 'Related Reward', 'goodbids' ),
							esc_url( get_edit_post_link( $reward_id ) ),
							esc_html( get_the_title( $reward_id ) ),
							esc_html( $reward_id )
						);
					},
					$screen->id,
					'side'
				);
			}
		);
	}

	/**
	 * Create a new Authentication Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function create_auth_page(): void {
		add_action(
			'woocommerce_page_created',
			function ( int $page_id, array $page_data ): void {
				if ( empty( $page_data['post_name'] ) || 'my-account' !== $page_data['post_name'] ) {
					return;
				}

				$auth_page = [
					'post_title'     => __( 'Authentication', 'goodbids' ),
					'post_name'      => 'authentication',
					'post_status'    => 'publish',
					'post_type'      => 'page',
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
					'post_content'   => '<!-- wp:acf/authentication {"name":"acf/authentication","mode":"preview"} /-->',
					'post_parent'    => $page_id,
				];

				$auth_page_id = wp_insert_post( $auth_page, true );

				if ( is_wp_error( $auth_page_id ) ) {
					Log::error( $auth_page_id->get_error_message(), compact( 'auth_page' ) );
				} elseif ( ! $auth_page_id ) {
					Log::warning( 'Failed to create Authentication Page.', compact( 'auth_page' ) );
					return;
				}

				update_option( 'woocommerce_authentication_page_id', $auth_page_id );
			},
			6,
			2
		);
	}

	/**
	 * Add Authentication Page setting to WooCommerce Advanced Settings
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_auth_page_setting(): void {
		add_filter(
			'woocommerce_settings_pages',
			function ( array $settings ): array {
				$auth_setting = [
					'title'    => __( 'Authentication', 'goodbids' ),
					'desc'     => __( 'Page contents: GoodBids Authentication Block.', 'goodbids' ),
					'id'       => 'woocommerce_authentication_page_id',
					'type'     => 'single_select_page_with_search',
					'default'  => '',
					'class'    => 'wc-page-search',
					'css'      => 'min-width:300px;',
					'args'     => [
						'exclude' => [ // phpcs:ignore
							wc_get_page_id( 'cart' ),
							wc_get_page_id( 'checkout' ),
							wc_get_page_id( 'my-account' ),
						],
					],
					'desc_tip' => true,
					'autoload' => false,
				];

				$new_settings = [];

				foreach ( $settings as $setting ) {
					$new_settings[] = $setting;

					if ( 'woocommerce_myaccount_page_id' === $setting['id'] ) {
						$new_settings[] = $auth_setting;
					}
				}

				return $new_settings;
			}
		);
	}

	/**
	 * Add a post display state for special GoodBids pages.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function display_post_states(): void {
		add_filter(
			'display_post_states',
			function ( array $post_states, WP_Post $post ): array {
				if ( wc_get_page_id( 'authentication' ) === $post->ID ) {
					$post_states['wc_page_for_authentication'] = __( 'Authentication Page', 'goodbids' );
				}

				return $post_states;
			},
			10,
			2
		);
	}

	/**
	 * Remove Unneeded WooCommerce Features on the Main Site
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function main_site_cleanup(): void {
		add_action(
			'admin_menu',
			function () {
				if ( ! is_main_site() ) {
					return;
				}

				// Remove Products Menu
				remove_menu_page( 'edit.php?post_type=product' );

				// Remove WooCommerce Submenus
				remove_submenu_page( 'woocommerce', 'wc-orders' );
				remove_submenu_page( 'woocommerce', 'edit.php?post_type=shop_coupon' );
				remove_submenu_page( 'woocommerce', 'wc-reports' );
			},
			200
		);

		// Remove WooCommerce Analytics Menu.
		add_filter(
			'option_woocommerce_analytics_enabled',
			function ( string $enabled ): string {
				if ( ! is_main_site() ) {
					return $enabled;
				}

				return 'no';
			}
		);

		// Disable WooCommerce Order Attribution
		add_filter(
			'option_woocommerce_feature_order_attribution_enabled',
			function ( string $enabled ): string {
				if ( ! is_main_site() ) {
					return $enabled;
				}

				return 'no';
			}
		);

		// Disable WooCommerce Marketplace Suggestions
		add_filter(
			'option_woocommerce_show_marketplace_suggestions',
			function ( string $enabled ): string {
				if ( ! is_main_site() ) {
					return $enabled;
				}

				return 'no';
			}
		);

		// Remove some of the Settings Tabs.
		add_filter(
			'woocommerce_settings_tabs_array',
			function ( array $tabs ): array {
				if ( ! is_main_site() ) {
					return $tabs;
				}

				unset( $tabs['products'] );
				unset( $tabs['tax'] );
				unset( $tabs['shipping'] );
				unset( $tabs['integration'] );
				return $tabs;
			},
			100
		);
	}

	/**
	 * Limit access from WooCommerce pages
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function limit_access_to_pages(): void {
		add_filter(
			'goodbids_restrict_pages',
			function ( array $pages ): array {
				$wc_pages = array_filter(
					[
						intval( get_option( 'woocommerce_cart_page_id' ) ),
						intval( get_option( 'woocommerce_checkout_page_id' ) ),
						intval( get_option( 'woocommerce_myaccount_page_id' ) ),
						intval( get_option( 'woocommerce_shop_page_id' ) ),
						intval( get_option( 'woocommerce_view_order_page_id' ) ),
						intval( get_option( 'woocommerce_authentication_page_id' ) ),
					]
				);

				return array_merge( $pages, $wc_pages );
			}
		);
	}
}
