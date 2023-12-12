<?php
/**
 * WooCommerce Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins;

/**
 * Class for WooCommerce
 *
 * @since 1.0.0
 */
class WooCommerce {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $slug = 'woocommerce';

	/**
	 * Initialize WooCommerce Functionality
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! goodbids()->is_plugin_active( $this->slug ) ) {
			return;
		}

		$this->configure_new_site();
		$this->create_auth_page();
		$this->add_auth_page_setting();
	}

	/**
	 * Configure WooCommerce settings for new sites.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function configure_new_site() : void {
		add_action(
			'goodbids_init_site',
			function ( int $site_id ) : void {
				// Disable Guest Checkout.
				update_option( 'woocommerce_enable_guest_checkout', 'no' );

				// Enable Log in during Checkout.
				update_option( 'woocommerce_enable_checkout_login_reminder', 'yes' );

				// Enable Account Creation during Checkout.
				update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'yes' );

				// Enable Account Creation from My Account Page.
				update_option( 'woocommerce_enable_myaccount_registration', 'yes' );

				// Allow for personal data removal.
				update_option( 'woocommerce_erasure_request_removes_order_data', 'yes' );
				update_option( 'woocommerce_allow_bulk_remove_personal_data', 'yes' );
			},
			5
		);
	}

	/**
	 * Create a new Authentication Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function create_auth_page() : void {
		add_action(
			'woocommerce_page_created',
			function ( int $page_id, array $page_data ) : void {
				if ( empty( $page_data['post_name'] ) || 'my-account' !== $page_data['post_name'] ) {
					return;
				}

				$auth_page_id = wp_insert_post(
					[
						'post_title'     => __( 'Authentication', 'goodbids' ),
						'post_name'      => 'authentication',
						'post_status'    => 'publish',
						'post_type'      => 'page',
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
						'post_content'   => '<!-- wp:acf/authentication {"name":"acf/authentication","mode":"preview"} /-->',
						'post_parent'    => $page_id,
					],
					true
				);

				if ( ! $auth_page_id ) {
					// TODO: Log Error.
					return;
				}

				update_option( 'woocommerce_authentication_page_id', $auth_page_id );
			},
			6
		);
	}

	/**
	 * Add Authentication Page setting to WooCommerce Advanced Settings
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_auth_page_setting() : void {
		add_filter(
			'woocommerce_settings_pages',
			function ( array $settings ) : array {
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
}
