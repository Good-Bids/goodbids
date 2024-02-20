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
use GoodBids\Plugins\WooCommerce\Emails\AuctionClosed;
use GoodBids\Plugins\WooCommerce\Emails\AuctionIsLive;
use GoodBids\Plugins\WooCommerce\Emails\AuctionIsLiveAdmin;
use GoodBids\Plugins\WooCommerce\Emails\AuctionOutbid;
use GoodBids\Plugins\WooCommerce\Emails\AuctionRewardReminder;
use GoodBids\Plugins\WooCommerce\Emails\AuctionSummaryAdmin;
use GoodBids\Plugins\WooCommerce\Emails\AuctionWinnerConfirmation;
use GoodBids\Plugins\WooCommerce\Orders;
use WC_Product;
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
		$this->admin    = new Admin();
		$this->coupons  = new Coupons();
		$this->orders   = new Orders();
		$this->cart     = new Cart();
		$this->checkout = new Checkout();

		// Init API Endpoints.
		$this->setup_api_endpoints();

		// Let WooCommerce know about some custom meta keys.
		$this->register_meta_keys();

		// Trim zeros on the prices
		$this->price_trim_zeros();

		// New Site Setup.
		$this->configure_new_site();

		// Auth Redirects.
		// $this->authentication_redirect();
		// $this->prevent_wp_login_access();
		$this->redirect_after_login();
		$this->redirect_after_registration();

		// Adjust Out of Stock Error to reflect Duplicate Bids.
		$this->adjust_out_of_stock_error();

		// Use GoodBids templates when available.
		$this->load_woocommerce_templates();

		// Adjust the WooCommerce Checkout Actions block.
		$this->modify_checkout_actions_block();

		// Adjust the Login page.
		$this->modify_login_page();

		// Add email notifications.
		$this->setup_email_notifications();

		// Adding Custom Email Styles
		$this->add_custom_email_styles();
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
			'goodbids_init_site',
			function (): void {
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

				// Update email base color.
				update_option( 'woocommerce_email_base_color', '#0A3624' );

				// Update email footer text.
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
	 * Add a custom email to the list of emails WooCommerce
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function setup_email_notifications(): void {
		add_filter(
			'woocommerce_email_classes',
			function ( array $email_classes ): array {
				$goodbids_emails = [
					'AuctionClosed'             => new AuctionClosed(),
					'AuctionIsLive'             => new AuctionIsLive(),
					'AuctionIsLiveAdmin'        => new AuctionIsLiveAdmin(),
					'AuctionOutbid'             => new AuctionOutbid(),
					'AuctionRewardReminder'     => new AuctionRewardReminder(),
					'AuctionSummaryAdmin'       => new AuctionSummaryAdmin(),
					'AuctionWinnerConfirmation' => new AuctionWinnerConfirmation(),
				];

				return array_merge( $email_classes, $goodbids_emails );
			}
		);
	}

	/**
	 * Retrieve the current add-to-cart product.
	 *
	 * @since 1.0.0
	 *
	 * @param ?WC_Product $product
	 *
	 * @return ?WC_Product
	 */
	public function get_add_to_cart_product( ?WC_Product $product = null ): ?WC_Product {
		// Sometimes this filter does not pass a product.
		if ( ! $product && ! empty( $_REQUEST['add-to-cart'] ) ) { // phpcs:ignore
			$product = wc_get_product( intval( sanitize_text_field( wp_unslash( $_REQUEST['add-to-cart'] ) ) ) ); // phpcs:ignore
		}

		if ( ! $product instanceof WC_Product ) {
			return null;
		}

		return $product;
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
	}

	/**
	 * Add custom email styles
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function add_custom_email_styles(): void {
		add_filter(
			'woocommerce_email_styles',
			function ( string $css ) {
				$custom_css = sprintf(
					'.button-wrapper {
						text-align: center;
						margin: 42px 0 12px 0 !important;
					}
					.button {
						display: inline-block;
						margin: 0 auto;
						background-color: %s;
						border-radius: 3px;
						color: #ffffff;
						padding: 8px 20px;
						text-decoration: none;
					}',
					esc_attr( get_option( 'woocommerce_email_base_color' ) )
				);

				return $css . $custom_css;
			},
			10
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
						'<div class="flex flex-col items-center mo-oauth-login"><p class="w-full font-extrabold">%s</p>%s</div>',
						esc_html__( 'Or login with one of these providers', 'goodbids' ),
						do_shortcode( '[mo_oauth_login]' ),
					);
				}
			}
		);
	}
}
