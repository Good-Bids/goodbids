<?php
/**
 * WooCommerce Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins;

use GoodBids\Frontend\Notices;
use GoodBids\Plugins\WooCommerce\Account;
use GoodBids\Plugins\WooCommerce\Admin;
use GoodBids\Plugins\WooCommerce\API\Credentials;
use GoodBids\Plugins\WooCommerce\Cart;
use GoodBids\Plugins\WooCommerce\Checkout;
use GoodBids\Plugins\WooCommerce\Coupons;
use GoodBids\Plugins\WooCommerce\Emails;
use GoodBids\Plugins\WooCommerce\Orders;
use GoodBids\Plugins\WooCommerce\Stripe;
use GoodBids\Plugins\WooCommerce\Taxes;
use WP_Error;

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
	const AUCTION_META_KEY = '_goodbids_auction_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE_META_KEY = '_goodbids_product_type';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const MICROTIME_META_KEY = '_goodbids_order_microtime';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $slug = 'woocommerce';

	/**
	 * @since 1.0.0
	 * @var ?Account
	 */
	public ?Account $account = null;

	/**
	 * @since 1.0.0
	 * @var ?Emails
	 */
	public ?Emails $emails = null;

	/**
	 * @since 1.0.0
	 * @var ?Admin
	 */
	public ?Admin $admin = null;

	/**
	 * @since 1.0.0
	 * @var ?Coupons
	 */
	public ?Coupons $coupons = null;

	/**
	 * @since 1.0.0
	 * @var ?Orders
	 */
	public ?Orders $orders = null;

	/**
	 * @since 1.0.0
	 * @var ?Cart
	 */
	public ?Cart $cart = null;

	/**
	 * @since 1.0.0
	 * @var ?Checkout
	 */
	public ?Checkout $checkout = null;

	/**
	 * @since 1.0.0
	 * @var ?Taxes
	 */
	public ?Taxes $taxes = null;

	/**
	 * Initialize WooCommerce Functionality
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! goodbids()->is_plugin_active( $this->slug ) ) {
			return;
		}

		// Init Submodules.
		$this->account  = new Account();
		$this->emails   = new Emails();
		$this->admin    = new Admin();
		$this->coupons  = new Coupons();
		$this->orders   = new Orders();
		$this->cart     = new Cart();
		$this->checkout = new Checkout();
		$this->taxes    = new Taxes();

		// Some Stripe Tweaks.
		new Stripe();

		// Init API Endpoints.
		$this->setup_api_endpoints();

		// Let WooCommerce know about some custom meta keys.
		$this->register_meta_keys();

		// Trim zeros on the prices
		$this->price_trim_zeros();

		// New Site Setup.
		$this->configure_new_site();

		// Change the default registration URL.
		$this->modify_registration_url();

		// Auth Redirects.
		// $this->authentication_redirect();
		// $this->prevent_wp_login_access();
		$this->redirect_after_login();
		$this->redirect_after_registration();

		// Disable WooCommerce ReserveStock functionality.
		$this->disable_reserve_stock();

		// Adjust Out of Stock Error to reflect Duplicate Bids.
		$this->adjust_out_of_stock_error();

		// Use GoodBids templates when available.
		$this->load_woocommerce_templates();

		// Adjust the WooCommerce Checkout Actions block.
		$this->modify_checkout_actions_block();

		// Adjust the Login page.
		$this->modify_login_page();
		$this->modify_register_page();

		// Skip some of the Setup Tasks
		$this->skip_setup_tasks();
	}

	/**
	 * Register WooCommerce API Custom endpoints.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function setup_api_endpoints(): void {
		add_filter(
			'woocommerce_rest_api_get_rest_namespaces',
			function ( $controllers ): array {
				$v3_controllers       = $controllers['wc/v3'] ?? [];
				$controllers['wc/v3'] = array_merge( $v3_controllers, $this->get_v3_controllers() );

				return $controllers;
			}
		);
	}

	/**
	 * Returns the WooCommerce v3 API Controllers.
	 *
	 * @since 1.0.0
	 *
	 * @return string[]
	 */
	private function get_v3_controllers(): array {
		return [
			'credentials' => Credentials::class,
		];
	}

	/**
	 * Register Meta Keys with WordPress.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_meta_keys(): void {
		add_action(
			'init',
			function (): void {
				register_meta(
					'post',
					self::AUCTION_META_KEY,
					[
						'object_subtype' => 'shop_order',
						'type'           => 'integer',
						'single'         => true,
						'show_in_rest'   => true,
					]
				);

				register_meta(
					'post',
					self::TYPE_META_KEY,
					[
						'object_subtype' => 'shop_order',
						'type'           => 'string',
						'single'         => true,
						'show_in_rest'   => true,
					]
				);
			}
		);
	}

	/**
	 * Trim zeros in price decimals
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function price_trim_zeros(): void {
		add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
	}

	/**
	 * Configure WooCommerce settings for new sites.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function configure_new_site(): void {
		add_action(
			'goodbids_initialize_site',
			function (): void {
				// Registration Settings
				update_option( 'woocommerce_enable_guest_checkout', 'no' );
				update_option( 'woocommerce_enable_checkout_login_reminder', 'yes' );
				update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'yes' );
				update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
				update_option( 'woocommerce_registration_generate_username', 'no' );
				update_option( 'woocommerce_registration_generate_password', 'no' );

				// Allow for personal data removal.
				update_option( 'woocommerce_erasure_request_removes_order_data', 'yes' );
				update_option( 'woocommerce_allow_bulk_remove_personal_data', 'yes' );

				// Shipping and Taxes
				update_option( 'woocommerce_calc_taxes', 'yes' );
				update_option( 'woocommerce_weight_unit', 'lbs' );
				update_option( 'woocommerce_dimension_unit', 'in' );
				update_option( 'woocommerce_tax_total_display', 'single' );

				// Disable Reviews
				update_option( 'woocommerce_enable_reviews', 'no' );

				// Inventory Management
				update_option( 'woocommerce_notify_low_stock', 'no' );
				update_option( 'woocommerce_notify_no_stock', 'no' );

				// Disable Marketplace
				update_option( 'woocommerce_show_marketplace_suggestions', 'no' );

				/**
				 * Email Settings
				 */

				// From Email
				$from_email = sprintf( 'no-reply@%s', wp_parse_url( home_url(), PHP_URL_HOST ) );
				update_option( 'woocommerce_email_from_name', get_bloginfo( 'name' ) );
				update_option( 'woocommerce_email_from_address', $from_email );

				// Update email base color.
				update_option( 'woocommerce_email_base_color', '#0A3624' );

				// Update email footer text. TODO: Should we set up tokens for these?
				$email_footer_text = sprintf(
					'%s <p>GoodBids for <a href="{site_url}">{site_title}</a>  —  %s | %s</p>',
					get_custom_logo( get_main_site_id() ),
					goodbids()->sites->get_terms_conditions_link(),
					goodbids()->sites->get_privacy_policy_link()
				);
				update_option( 'woocommerce_email_footer_text', $email_footer_text );
			},
			5
		);
	}

	/**
	 * Adjust the default registration URL.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function modify_registration_url(): void {
		add_filter(
			'register_url',
			fn () => wc_get_page_permalink( 'myaccount' ),
			5
		);
	}

	/**
	 * Redirect to Authentication Page if user is not logged in.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function authentication_redirect(): void {
		add_action(
			'template_redirect',
			function (): void {
				global $wp;

				// Make sure we have a My Account & Authentication page.
				$auth_page_id    = wc_get_page_id( 'authentication' );
				$account_page_id = wc_get_page_id( 'myaccount' );

				if ( ! $auth_page_id || ! $account_page_id ) {
					return;
				}

				// If logged in, perform WooCommerce Login Redirect.
				if ( is_user_logged_in() ) {
					if ( $auth_page_id === get_queried_object_id() ) {
						$redirect = apply_filters( 'woocommerce_login_redirect', wc_get_page_permalink( 'myaccount' ) );

						if ( $redirect ) {
							wp_safe_redirect( $redirect );
							exit;
						}
					}

					return;
				}

				if ( $account_page_id !== get_queried_object_id() ) {
					return;
				}

				$auth_page_url = wc_get_page_permalink( 'authentication' );

				if ( ! $auth_page_url ) {
					return;
				}

				$auth_page_url = add_query_arg(
					'redirect_to',
					urlencode( home_url( $wp->request ) ),
					$auth_page_url
				);

				wp_safe_redirect( $auth_page_url );
				exit;
			}
		);
	}

	/**
	 * Prevent access to WP Login page unless user can manage options.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function prevent_wp_login_access(): void {
		add_action(
			'login_head',
			function () {
				$request = ! empty( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( $_SERVER['REQUEST_URI'] ) : '';

				// Check if the current URL contains /wp-admin or /wp-login.php
				if ( ! str_contains( $request, '/wp-admin' ) && ! str_contains( $request, '/wp-login.php' ) ) {
					return;
				}

				// Allow logged-in users with manage_options permissions.
				if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
					return;
				}

				$auth_page_url = wc_get_page_permalink( 'authentication' );

				if ( ! $auth_page_url ) {
					return;
				}

				// Redirect to custom Auth page.
				wp_safe_redirect( $auth_page_url );
				exit;
			},
			2
		);
	}

	/**
	 * Handle post-login redirect.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function redirect_after_login(): void {
		add_filter(
			'woocommerce_login_redirect',
			function ( $redirect ) {
				if ( empty( $_REQUEST['redirect-to'] ) ) { // phpcs:ignore
					return $redirect;
				}

				return sanitize_text_field( urldecode( wp_unslash( $_REQUEST['redirect-to'] ) ) ); // phpcs:ignore
			},
			5
		);
	}

	/**
	 * Handle post-registration redirect.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function redirect_after_registration(): void {
		add_filter(
			'woocommerce_registration_redirect',
			function ( $redirect ) {
				if ( empty( $_REQUEST['redirect-to'] ) ) { // phpcs:ignore
					return $redirect;
				}

				return sanitize_text_field( urldecode( wp_unslash( $_REQUEST['redirect-to'] ) ) ); // phpcs:ignore
			},
			5
		);
	}

	/**
	 * Disable WooCommerce ReserveStock functionality.
	 *
	 * @since 1.0.1
	 *
	 * @return void
	 */
	private function disable_reserve_stock(): void {
		add_filter(
			'query',
			function ( string $query ): string {
				global $wpdb;
				if ( ! str_contains( $query, $wpdb->wc_reserved_stock ) || ! str_contains( $query, 'INSERT INTO' ) ) {
					return $query;
				}

				return 'SELECT 1';
			}
		);

		remove_action( 'woocommerce_checkout_order_created', 'wc_reserve_stock_for_order' );

		add_filter(
			'woocommerce_hold_stock_for_checkout',
			fn () => 'no'
		);

		add_filter(
			'option_woocommerce_hold_stock_minutes',
			fn () => 0
		);
	}

	/**
	 * Empty Cart and Modify Error when out of stock error is received.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function adjust_out_of_stock_error(): void {
		add_action(
			'wp_error_added',
			function ( string $code, string $message, mixed $data, WP_Error $wp_error ) {
				if ( 'woocommerce_rest_product_out_of_stock' !== $code ) {
					return;
				}

				$wp_error->remove( $code );
				WC()->cart->empty_cart();

				$notice = goodbids()->notices->get_notice( Notices::BID_ALREADY_PLACED );
				$wp_error->add( Notices::BID_ALREADY_PLACED, $notice['message'] );
			},
			10,
			4
		);
	}

	/**
	 * Disable the Return to Cart link on the Checkout page inside the Checkout Actions block.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function modify_checkout_actions_block(): void {
		add_filter(
			'render_block_data',
			function ( $parsed_block ) {
				if ( empty( $parsed_block['blockName'] ) || 'woocommerce/checkout-actions-block' !== $parsed_block['blockName'] || is_admin() ) {
					return $parsed_block;
				}

				$parsed_block['attrs']['showReturnToCart'] = false;

				return $parsed_block;
			}
		);
	}

	/**
	 * Load WooCommerce Templates from the GoodBids Views folder.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load_woocommerce_templates(): void {
		add_filter(
			'woocommerce_locate_template',
			function ( $template, $template_name, $template_path ): string {
				// Only override templates coming from WooCommerce.
				if ( 'woocommerce' !== trim( $template_path, '/' ) ) {
					return $template;
				}

				$goodbids_path = GOODBIDS_PLUGIN_PATH . 'views/woocommerce/' . $template_name;
				if ( file_exists( $goodbids_path ) ) {
					return $goodbids_path;
				}

				return $template;
			},
			8,
			3
		);

		add_filter(
			'wc_get_template',
			function ( $template, $template_name ): string {
				$goodbids_path = GOODBIDS_PLUGIN_PATH . 'views/woocommerce/' . $template_name;
				if ( file_exists( $goodbids_path ) ) {
					return $goodbids_path;
				}

				return $template;
			},
			8,
			2
		);
	}

	/**
	 * Modify Login Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function modify_login_page(): void {
		add_action(
			'woocommerce_login_form_end',
			function () {
				if ( ! is_user_logged_in() ) {
					printf(
						'<div class="flex flex-col items-center mo-oauth-login"><p class="w-full text-sm text-center">%s</p>%s</div>',
						esc_html__( 'Or continue with', 'goodbids' ),
						do_shortcode( '[mo_oauth_login]' ),
					);
					printf(
						'<p class="w-full text-sm text-center">%s %s %s %s.<p>',
						esc_html__( 'By logging in, you agree to GOODBIDS\'', 'goodbids' ),
						wp_kses_post( goodbids()->sites->get_terms_conditions_link() ),
						esc_html__( 'and', 'goodbids' ),
						wp_kses_post( goodbids()->sites->get_privacy_policy_link() )
					);
				}
			}
		);
	}

	/**
	 * Modify register Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function modify_register_page(): void {
		add_action(
			'woocommerce_register_form_end',
			function () {
				if ( ! is_user_logged_in() ) {
					printf(
						'<p class="w-full text-sm text-center">%s %s %s %s.<p>',
						esc_html__( 'By registering an account, you agree to GOODBIDS\'', 'goodbids' ),
						wp_kses_post( goodbids()->sites->get_terms_conditions_link() ),
						esc_html__( 'and', 'goodbids' ),
						wp_kses_post( goodbids()->sites->get_privacy_policy_link() )
					);
				}
			}
		);
	}

	/**
	 * Skip setup tasks
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function skip_setup_tasks(): void {
		$hide_setup_steps = function ( array $lists ) {
			// Hide Initial Steps
			$lists['setup']->visible = false;

			// Hide Things to do next.
			$lists['extended']->visible = false;

			return $lists;
		};

		add_filter( 'woocommerce_admin_experimental_onboarding_tasklists', $hide_setup_steps );
		add_filter( 'woocommerce_admin_onboarding_tasklists', $hide_setup_steps ); // Just in case.
	}
}
