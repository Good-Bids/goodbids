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

		$this->store_auction_id_in_cart();
		$this->store_auction_id_on_checkout();
		$this->auction_meta_box();
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
	 * Store the Auction ID in the Order Item Meta.
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
				$order = wc_get_order( $order_id );

				// Finder order items with Auction Meta.
				foreach ( $order->get_items() as $item ) {
					try {
						$auction_id = wc_get_order_item_meta( $item->get_id(), self::AUCTION_META_KEY );
					} catch (\Exception $e) {
						continue;
					}

					if ( ! $auction_id ) {
						continue;
					}

					update_post_meta( $order_id, self::AUCTION_META_KEY, $auction_id );
				}
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
	private function auction_meta_box(): void {
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
					[ $this, 'auction_info_meta_box' ],
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
	public function auction_info_meta_box(): void {
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
}
