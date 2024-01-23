<?php
/**
 * WooCommerce Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins;

use GoodBids\Auctions\Bids;
use GoodBids\Auctions\Rewards;
use GoodBids\Frontend\Notices;
use GoodBids\Plugins\WooCommerce\API\Credentials;
use GoodBids\Plugins\WooCommerce\Coupons;
use WC_Order_Item_Product;
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
	private string $slug = 'woocommerce';

	/**
	 * @since 1.0.0
	 * @var ?Coupons
	 */
	public ?Coupons $coupons = null;

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
		$this->coupons = new Coupons();

		// Init API Endpoints.
		$this->setup_api_endpoints();

		// New Site Setup.
		$this->configure_new_site();
		$this->create_auth_page();

		// Custom WooCommerce Settings.
		$this->add_auth_page_setting();
		$this->display_post_states();

		// Auth Redirects.
		// $this->authentication_redirect();
		// $this->prevent_wp_login_access();

		$this->restrict_cart_products();
		$this->redirect_after_login();

		$this->auto_empty_cart();
		$this->redirect_after_add_to_cart();
		$this->validate_bid();
		$this->adjust_out_of_stock_error();

		$this->store_auction_info_in_cart();
		$this->store_auction_info_on_checkout();

		// Use GoodBids templates when available.
		$this->load_woocommerce_templates();

		// Custom My Account pages.
		$this->add_free_bids_account_tab();

		// Auction Admin Meta box.
		$this->add_auction_meta_box();
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
	 * Configure WooCommerce settings for new sites.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function configure_new_site(): void {
		add_action(
			'goodbids_init_site',
			function ( int $site_id ): void {
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
			function ( array $post_states, \WP_Post $post ): array {
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
	 * Store the Auction ID in the Cart Item Meta.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function store_auction_info_in_cart(): void {
		add_action(
			'woocommerce_checkout_create_order_line_item',
			function ( WC_Order_Item_Product $item ): void {

				if ( ! $item->get_product_id() ) {
					return;
				}

				$auction_id   = goodbids()->auctions->get_auction_id_from_product_id( $item->get_product_id() );
				$product_type = goodbids()->auctions->get_product_type( $item->get_product_id() );

				if ( ! $auction_id || ! $product_type ) {
					return;
				}

				$item->update_meta_data( self::AUCTION_META_KEY, $auction_id );
				$item->update_meta_data( self::TYPE_META_KEY, $product_type );
			}
		);
	}

	/**
	 * Store the Auction ID in the Order Meta.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function store_auction_info_on_checkout(): void {
		add_action(
			'woocommerce_payment_complete',
			function ( int $order_id ) {
				$info = $this->get_order_auction_info( $order_id );

				if ( ! $info ) {
					// TODO: Log warning.
					return;
				}

				update_post_meta( $order_id, self::AUCTION_META_KEY, $info['auction_id'] );
				update_post_meta( $order_id, self::TYPE_META_KEY, $info['order_type'] );

				do_action( 'goodbids_order_payment_complete', $order_id, $info['auction_id'] );
			}
		);
	}

	/**
	 * Get the Order Auction ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return ?int
	 */
	public function get_order_auction_id( int $order_id = 0 ): ?int {
		$order_id = $order_id ?: get_the_ID();

		if ( ! $order_id ) {
			return null;
		}

		$auction_id = get_post_meta( $order_id, self::AUCTION_META_KEY, true );

		return intval( $auction_id ) ?: null;
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
			function (): void {
				$screen = get_current_screen();

				if ( 'woocommerce_page_wc-orders' !== $screen->id ) {
					return;
				}

				$order_id = ! empty( $_GET['id'] ) ? intval( sanitize_text_field( $_GET['id'] ) ) : false; // phpcs:ignore

				if ( ! $order_id || ! $this->get_order_auction_id( $order_id ) ) {
					return;
				}

				add_meta_box(
					'goodbids-auction-info',
					__( 'Auction Info', 'goodbids' ),
					[ $this, 'auction_meta_box' ],
					$screen->id,
					'side'
				);
			}
		);
	}

	/**
	 * Auction Info Meta Box
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function auction_meta_box(): void {
		$order_id   = ! empty( $_GET['id'] ) ? intval( sanitize_text_field( $_GET['id'] ) ) : false; // phpcs:ignore
		$auction_id = $this->get_order_auction_id( $order_id );

		printf(
			'<p><strong>%s</strong><br><a href="%s">%s</a> (ID: %s)</p>',
			esc_html__( 'Related Auction', 'goodbids' ),
			esc_url( get_edit_post_link( $auction_id ) ),
			esc_html( get_the_title( $auction_id ) ),
			esc_html( $auction_id )
		);

		$reward_id = goodbids()->auctions->rewards->get_product_id( $auction_id );

		printf(
			'<p><strong>%s</strong><br><a href="%s">%s</a> (ID: %s)</p>',
			esc_html__( 'Related Reward', 'goodbids' ),
			esc_url( get_edit_post_link( $reward_id ) ),
			esc_html( get_the_title( $reward_id ) ),
			esc_html( $reward_id )
		);
	}

	/**
	 * Get the Order Type.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return ?string
	 */
	public function get_order_type( int $order_id ): ?string {
		$type = get_post_meta( $order_id, self::TYPE_META_KEY, true );
		return $type ?: null;
	}

	/**
	 * Check if an Order is a Bid Order.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return bool
	 */
	public function is_bid_order( int $order_id ): bool {
		return Bids::ITEM_TYPE === $this->get_order_type( $order_id );
	}

	/**
	 * Check if an Order is a Reward Order.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return bool
	 */
	public function is_reward_order( int $order_id ): bool {
		return Rewards::ITEM_TYPE === $this->get_order_type( $order_id );
	}

	/**
	 * Check if an Order was placed using a Free Bid.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return bool
	 */
	public function is_free_bid_order( int $order_id ): bool {
		// Not a Bid Order.
		if ( Bids::ITEM_TYPE !== $this->get_order_type( $order_id ) ) {
			return false;
		}

		$order = wc_get_order( $order_id );

		// TODO: Temporary Solution until Free Bids are implemented.
		if ( 0 < $order->get_total( 'edit' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Validate Bids during checkout
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function validate_bid(): void {
		add_action(
			'woocommerce_store_api_checkout_update_order_from_request',
			function ( $order, $request = [] ) {
				if ( empty( $request ) ) {
					return;
				}

				$info = $this->get_order_auction_info( $order->get_id() );

				if ( ! $info ) {
					// TODO: Log warning.
					return;
				}

				// Make sure Auction has started and has not ended.
				if ( ! goodbids()->auctions->has_started( $info['auction_id'] ) ) {
					$notice = goodbids()->notices->get_notice( Notices::AUCTION_NOT_STARTED );
					wc_add_notice( $notice['message'], $notice['type'] );
					return;
				} elseif ( goodbids()->auctions->has_ended( $info['auction_id'] ) ) {
					$notice = goodbids()->notices->get_notice( Notices::AUCTION_HAS_ENDED );
					wc_add_notice( $notice['message'], $notice['type'] );
					return;
				}

				if ( Bids::ITEM_TYPE !== $info['order_type'] ) {
					return;
				}

				if ( $this->is_free_bid_order( $order->get_id() ) ) {
					if ( ! goodbids()->auctions->are_free_bids_allowed( $info['auction_id'] ) ) {
						$notice = goodbids()->notices->get_notice( Notices::FREE_BIDS_NOT_ELIGIBLE );
						wc_add_notice( $notice['message'], $notice['type'] );
						return;
					}

					if ( ! goodbids()->users->get_available_free_bid_count() ) {
						$notice = goodbids()->notices->get_notice( Notices::NO_AVAILABLE_FREE_BIDS );
						wc_add_notice( $notice['message'], $notice['type'] );
					}
				}
			},
			10,
			2
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
			function ( string $code, string $message, array $data, WP_Error $wp_error ) {
				if ( 'woocommerce_rest_product_out_of_stock' !== $code || ! is_checkout() ) {
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
	 * Get the Auction info from the Order.
	 *
	 * @param int $order_id
	 *
	 * @since 1.0.0
	 *
	 * @return ?array
	 */
	public function get_order_auction_info( int $order_id ): ?array {
		$order         = wc_get_order( $order_id );
		$auction_id    = $this->get_order_auction_id( $order_id );
		$order_type    = $this->get_order_type( $order_id );
		$uses_free_bid = $this->is_free_bid_order( $order_id );

		// Return early if we already have this info.
		if ( $auction_id && $order_type ) {
			return [
				'auction_id'    => $auction_id,
				'order_type'    => $order_type,
				'uses_free_bid' => $uses_free_bid,
			];
		}

		// Find order items with Auction Meta.
		foreach ( $order->get_items() as $item ) {
			try {
				$auction_id = wc_get_order_item_meta( $item->get_id(), self::AUCTION_META_KEY );
				$order_type = wc_get_order_item_meta( $item->get_id(), self::TYPE_META_KEY );
			} catch ( \Exception $e ) {
				continue;
			}

			if ( $auction_id && $order_type ) {
				break;
			}
		}

		if ( ! $auction_id || ! $order_type ) {
			// TODO: Log warning.
			return null;
		}

		return [
			'auction_id'    => $auction_id,
			'order_type'    => $order_type,
			'uses_free_bid' => $uses_free_bid,
		];
	}

	/**
	 * Empty cart before adding a Bid or Reward product.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function auto_empty_cart(): void {
		add_filter(
			'woocommerce_add_to_cart_handler',
			function ( $handler, $product ) {
				if ( ! goodbids()->auctions->get_product_type( $product->get_id() ) ) {
					return $handler;
				}

				WC()->cart->empty_cart();

				return $handler;
			},
			10,
			2
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
	 * Redirect after adding bids and reward products to cart
	 * to remove the add-to-cart and use-free-bid url parameters.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function redirect_after_add_to_cart(): void {
		// No need to redirect.
		if ( ! isset( $_REQUEST['add-to-cart'] ) && ! isset( $_REQUEST['use-free-bid'] ) ) { // phpcs:ignore
			return;
		}

		add_filter(
			'woocommerce_add_to_cart_redirect',
			function ( string $url, ?WC_Product $product ): string {
				if ( wp_doing_ajax() ) {
					return $url;
				}

				$product = $this->get_add_to_cart_product( $product );

				if ( ! $product ) {
					return $url;
				}

				if ( ! goodbids()->auctions->get_product_type( $product->get_id() ) ) {
					return $url;
				}

				return wc_get_checkout_url();
			},
			15,
			2
		);

		// Fallback for when the above filter does not work.
		add_action(
			'template_redirect',
			function (): void {
				$product = $this->get_add_to_cart_product();

				if ( ! $product ) {
					return;
				}

				if ( ! goodbids()->auctions->get_product_type( $product->get_id() ) ) {
					return;
				}

				wp_safe_redirect( wc_get_checkout_url() );
				exit;
			},
			15,
			2
		);
	}

	/**
	 * Restrict our products to authenticated users.
	 * Make sure Reward Product is only available to the Auction Winner.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function restrict_cart_products(): void {
		add_filter(
			'woocommerce_add_to_cart_validation',
			function ( $passed, $product_id ) {
				if ( ! goodbids()->auctions->get_product_type( $product_id ) ) {
					return $passed;
				}

				// Auth check.
				$auth_url = wc_get_page_permalink( 'myaccount' );

				if ( ! is_user_logged_in() ) {
					// Redirect to login page with Add to Cart as the redirect.
					$checkout_url = wc_get_page_permalink( 'checkout' );
					$redirect_to  = add_query_arg( 'add-to-cart', $product_id, $checkout_url );

					$args     = [
						'redirect-to' => urlencode( $redirect_to ),
						'gb-notice'   => Notices::NOT_AUTHENTICATED,
					];
					$redirect = add_query_arg( $args, $auth_url );

					wp_safe_redirect( $redirect );
					exit;
				}

				$auction_id = goodbids()->auctions->get_auction_id_from_product_id( $product_id );

				if ( ! $auction_id ) {
					$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_NOT_FOUND, $auth_url );
					wp_safe_redirect( $redirect );
					exit;
				}

				$auction_url = get_permalink( $auction_id );

				if ( Bids::ITEM_TYPE === goodbids()->auctions->get_product_type( $product_id ) ) {
					if ( ! goodbids()->auctions->has_started( $auction_id ) ) {
						$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_NOT_STARTED, $auction_url );
						wp_safe_redirect( $redirect );
						exit;
					}

					if ( goodbids()->auctions->has_ended( $auction_id ) ) {
						$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_HAS_ENDED, $auction_url );
						wp_safe_redirect( $redirect );
						exit;
					}

					// Nothing left to do for bid items.
					return $passed;
				}

				/**
				 * Reward Items
				 */
				if ( ! goodbids()->auctions->has_ended( $auction_id ) ) {
					$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_NOT_ENDED, $auction_url );
					wp_safe_redirect( $redirect );
					exit;
				}

				if ( ! goodbids()->auctions->is_current_user_winner( $auction_id ) ) {
					$redirect = add_query_arg( 'gb-notice', Notices::NOT_AUCTION_WINNER, $auction_url );
					wp_safe_redirect( $redirect );
					exit;
				}

				if ( goodbids()->auctions->rewards->is_redeemed( $auction_id ) ) {
					$redirect = add_query_arg( 'gb-notice', Notices::REWARD_ALREADY_REDEEMED, $auction_url );
					wp_safe_redirect( $redirect );
					exit;
				}

				return $passed;
			},
			10,
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
	 * Create a new Free Bids tab on My Account page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_free_bids_account_tab(): void {
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
}
