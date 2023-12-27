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
		$this->display_post_states();

//		$this->authentication_redirect();
//		$this->prevent_wp_login_access();

		$this->auto_empty_cart();
		$this->validate_bid();

		$this->store_auction_id_in_cart();
		$this->store_auction_id_on_checkout();
		$this->redirect_after_bid_checkout();

		$this->add_auction_meta_box();
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

	/**
	 * Add a post display state for special GoodBids pages.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function display_post_states() : void {
		add_filter(
			'display_post_states',
			function ( array $post_states, \WP_Post $post ) : array {
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
	private function authentication_redirect() : void {
		add_action(
			'template_redirect',
			function () : void {
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
	private function prevent_wp_login_access() : void {
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
	private function store_auction_id_in_cart(): void {
		add_action(
			'woocommerce_checkout_create_order_line_item',
			function ( \WC_Order_Item_Product $item, string $cart_item_key, array $values, \WC_Order $order ) {

				if ( ! $item->get_product_id() ) {
					return;
				}

				$product_type = goodbids()->auctions->get_product_type( $item->get_product_id() );
				$auction_id   = false;

				if ( 'bids' === $product_type ) {
					$auction_id = goodbids()->auctions->bids->get_auction_id( $item->get_product_id() );

				} elseif ( 'rewards' === $product_type ) {
					// TODO: In another ticket.
				}

				if ( ! $auction_id ) {
					return;
				}

				$item->update_meta_data( self::AUCTION_META_KEY, $auction_id );
				$item->update_meta_data( self::TYPE_META_KEY, $product_type );
			},
			10,
			4
		);
	}

	/**
	 * Store the Auction ID in the Order Meta.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function store_auction_id_on_checkout(): void {
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
	 * Redirect back to Auction after Checkout.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function redirect_after_bid_checkout(): void {
		add_action(
			'woocommerce_thankyou',
			function ( int $order_id ): void {
				if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || headers_sent() ) {
					return;
				}

				if ( ! $this->is_bid_order( $order_id ) ) {
					return;
				}

				$auction_id = $this->get_order_auction_id( $order_id );

				// TODO: Check if Auction has ended.

				wp_safe_redirect( get_permalink( $auction_id ) );
				exit;
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

		$reward_id = goodbids()->auctions->get_reward_product_id( $auction_id );

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
	 * @return string
	 */
	public function get_order_type( int $order_id ): string {
		$type = get_post_meta( $order_id, self::TYPE_META_KEY, true );
		return $type ?: 'unknown';
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
		return 'bids' === $this->get_order_type( $order_id );
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
		return 'rewards' === $this->get_order_type( $order_id );
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
			function( $order, $request = [] ) {
				if ( empty( $request ) ) {
					return;
				}

				$info = $this->get_order_auction_info( $order->get_id() );

				if ( ! $info ) {
					// TODO: Log warning.
					return;
				}

				if ( ! goodbids()->auctions->has_started( $info['auction_id'] ) ) {
					// TODO: Move error to Settings Page.
					wc_add_notice( __( 'Auction has not started yet.', 'goodbids' ), 'error' );
				} elseif ( goodbids()->auctions->has_ended( $info['auction_id'] ) ) {
					// TODO: Move error to Settings Page.
					wc_add_notice( __( 'Auction has already ended.', 'goodbids' ), 'error' );
				}
			},
			10,
			2
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
		$order      = wc_get_order( $order_id );
		$auction_id = false;
		$order_type = false;

		// Find order items with Auction Meta.
		foreach ( $order->get_items() as $item ) {
			try {
				$auction_id = wc_get_order_item_meta( $item->get_id(), self::AUCTION_META_KEY );
				$order_type = wc_get_order_item_meta( $item->get_id(), self::TYPE_META_KEY );
			} catch (\Exception $e) {
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
			'auction_id' => $auction_id,
			'order_type' => $order_type,
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
}
