<?php
/**
 * GoodBids Nonprofit Onboarding
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use Automattic\WooCommerce\Internal\Admin\Onboarding\OnboardingProfile;

/**
 * Setup Class
 *
 * @since 1.0.0
 */
class Onboarding {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-onboarding';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_PARAM = 'step';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const IS_ONBOARDING_PARAM = 'gb-is-onboarding';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const DONE_ONBOARDING_PARAM = 'gb-onboarding-complete';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_TRANSIENT = 'gb-onboarding-step';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_INIT_ONBOARDING = 'init-onboarding';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_CREATE_STORE = 'create-store';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_SET_UP_PAYMENTS = 'set-up-payments';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_ACCESSIBILITY_CHECKER_LICENSE = 'activate-accessibility-checker';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_ONBOARDING_COMPLETE = 'onboarding-complete';

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $steps = [];

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( is_main_site() || is_network_admin() ) {
			return;
		}

		// Setup Onboarding Steps
		$this->init_steps();

		// Tweak Admin for Onboarding.
		$this->focused_setup();

		// Remove any admin notices for this page.
		$this->disable_admin_notices();

		// Add the menu page for the setup dashboard
		$this->add_menu_dashboard_page();

		// Enqueue Scripts
		$this->enqueue_scripts();

		// Perform redirects as needed
		$this->onboarding_redirect();

		// Adjustments for Onboarding Pages
		$this->stripe_adjustments();
		$this->woocommerce_adjustments();

		// Flag Onboarding as Completed.
		$this->maybe_mark_onboarding_completed();
	}

	/**
	 * Initialize the Onboarding Steps.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_steps(): void {
		add_action(
			'admin_init',
			function () {
				// Initialize Onboarding URL
				$init_onboarding_url = $this->get_url();

				// Store Setup URL
				$create_store_url = admin_url( 'admin.php?page=wc-admin&path=/setup-wizard&step=skip-guided-setup' );
				$create_store_url = add_query_arg( self::IS_ONBOARDING_PARAM, 1, $create_store_url );

				// Payments Setup URL
				$payments_url = admin_url( 'admin.php?page=wc-settings&tab=checkout&section=stripe&panel=settings' );
				$payments_url = add_query_arg( self::IS_ONBOARDING_PARAM, 1, $payments_url );

				// Accessibility Checker URL
				$accessibility_checker_url = admin_url( 'admin.php?page=accessibility_checker_settings&tab=license' );
				$accessibility_checker_url = add_query_arg( self::IS_ONBOARDING_PARAM, 1, $accessibility_checker_url );

				// Onboarding Complete URL
				$onboarding_complete_url = home_url();
				$onboarding_complete_url = add_query_arg( self::DONE_ONBOARDING_PARAM, 1, $onboarding_complete_url );

				$this->steps = [
					self::STEP_INIT_ONBOARDING                  => [
						'url'          => $init_onboarding_url,
						'is_complete'  => get_transient( self::STEP_TRANSIENT ) || goodbids()->network->nonprofits->is_onboarded(),
						'is_step_page' => $this->is_onboarding_page(),
						'callback'     => null,
					],
					self::STEP_CREATE_STORE                  => [
						'url'          => $create_store_url,
						'is_complete'  => $this->completed_wc_onboarding(),
						'is_step_page' => $this->is_wc_onboarding_page(),
						'callback'     => [ $this, 'mark_wc_onboarding_skipped' ],
					],
					self::STEP_SET_UP_PAYMENTS               => [
						'url'          => $payments_url,
						'is_complete'  => $this->completed_payments_onboarding(),
						'is_step_page' => $this->is_stripe_page(),
						'callback'     => null,
					],
					self::STEP_ACCESSIBILITY_CHECKER_LICENSE => [
						'url'          => $accessibility_checker_url,
						'is_complete'  => $this->completed_accessibility_license(),
						'is_step_page' => goodbids()->accessibility->is_license_page(),
						'callback'     => null,
					],
					self::STEP_ONBOARDING_COMPLETE           => [
						'url'          => $onboarding_complete_url,
						'is_complete'  => goodbids()->network->nonprofits->is_onboarded(),
						'is_step_page' => false,
						'callback'     => null,
					],
				];
			},
			8
		);
	}

	/**
	 * Focus the Onboarding Setup
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function focused_setup(): void {
		add_action(
			'admin_menu',
			function () {
				if ( goodbids()->network->nonprofits->is_onboarded() || is_super_admin() ) {
					// Setup Guide URL
					$setup_guide_url = admin_url( 'admin.php?page=' . Guide::PAGE_SLUG );
					$setup_guide_url = add_query_arg( self::DONE_ONBOARDING_PARAM, 1, $setup_guide_url );

					if ( $this->is_onboarding_page() ) {
						wp_safe_redirect( $setup_guide_url );
						exit;
					}

					return;
				}

				global $menu;

				foreach ( $menu as &$item ) {
					if ( self::PAGE_SLUG !== $item[2] ) {
						if ( isset( $item[4] ) ) {
							$item[4] .= ' hidden';
						} else {
							$item[4] = 'hidden';
						}
					}
				}
			},
			99999
		);
	}

	/**
	 * Add the menu page for the setup dashboard
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_menu_dashboard_page(): void {
		add_action(
			'admin_menu',
			function () {
				// Disable for the main site.
				if ( is_main_site() || goodbids()->network->nonprofits->is_onboarded() ) {
					return;
				}

				add_menu_page(
					__( 'GoodBids Onboarding', 'goodbids' ),
					__( 'Onboarding', 'goodbids' ),
					'manage_options',
					self::PAGE_SLUG,
					[ $this, 'nonprofit_onboarding_page' ],
					'dashicons-admin-site-alt3',
					1.1
				);
			}
		);
	}

	/**
	 * Nonprofit Onboarding Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function nonprofit_onboarding_page(): void {
		goodbids()->load_view( 'admin/setup/onboarding.php', [ 'nonprofit_onboarding_id' => self::PAGE_SLUG ] );
	}

	/**
	 * Get the URL for the nonprofit onboarding page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $step
	 *
	 * @return string
	 */
	public function get_url( string $step = '' ): string {
		$url = admin_url( 'admin.php?page=' . self::PAGE_SLUG );

		if ( $step ) {
			$url = add_query_arg( self::STEP_PARAM, $step, $url );
		}

		return $url;
	}

	/**
	 * Check if the current page is the nonprofit onboarding page.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_onboarding_page(): bool {
		global $pagenow;

		if ( 'admin.php' !== $pagenow || empty( $_GET['page'] ) ) { // phpcs:ignore
			return false;
		}

		$page = sanitize_text_field( wp_unslash( $_GET['page'] ) ); // phpcs:ignore

		if ( self::PAGE_SLUG !== $page ) {
			return false;
		}

		return true;
	}

	/**
	 * Enqueue Admin Scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		add_filter(
			'goodbids_admin_script_dependencies',
			function( array $dependencies ): array {
				if ( ! $this->is_onboarding_page() ) {
					return $dependencies;
				}

				// Get the asset file.
				$asset_file = GOODBIDS_PLUGIN_PATH . 'build/views/nonprofit-onboarding.asset.php';
				if ( file_exists( $asset_file ) ) {
					$script = require $asset_file;
				} else {
					$script = [
						'dependencies' => [ 'react', 'react-dom', 'wp-api-fetch', 'wp-element', 'wp-i18n' ],
						'version'      => goodbids()->get_version()
					];
				}

				// Register the Onboarding script.
				wp_register_script(
					self::PAGE_SLUG,
					GOODBIDS_PLUGIN_URL . 'build/views/nonprofit-onboarding.js',
					$script['dependencies'],
					$script['version'],
					[ 'strategy' => 'defer' ]
				);

				// Localize Vars.
				wp_localize_script( self::PAGE_SLUG, 'gbNonprofitOnboarding', $this->get_js_vars() );

				// Set translations.
				wp_set_script_translations( self::PAGE_SLUG, 'goodbids' );

				// Add as a dependency.
				$dependencies[] = self::PAGE_SLUG;

				return $dependencies;
			}
		);
	}

	/**
	 * Localized JS Variables
	 *
	 * See globals.d.ts for matching TypeScript types.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_js_vars(): array {
		// Setup Guide URL
		$setup_guide_url = admin_url( 'admin.php?page=' . Guide::PAGE_SLUG );
		$setup_guide_url = add_query_arg( self::DONE_ONBOARDING_PARAM, 1, $setup_guide_url );

		// Admin URL
		$admin_url = admin_url();
		$admin_url = add_query_arg( self::DONE_ONBOARDING_PARAM, 1, $admin_url );

		return [
			'appID'     => self::PAGE_SLUG,
			'stepParam' => self::STEP_PARAM,

			'stepOptions'             => array_keys( $this->steps ),
			'createStoreUrl'          => $this->steps[ self::STEP_CREATE_STORE ]['url'],
			'setUpPaymentsUrl'        => $this->steps[ self::STEP_SET_UP_PAYMENTS ]['url'],
			'accessibilityCheckerUrl' => $this->steps[ self::STEP_ACCESSIBILITY_CHECKER_LICENSE ]['url'],
			'onboardingCompleteUrl'   => $this->steps[ self::STEP_ONBOARDING_COMPLETE ]['url'],

			'setupGuideUrl' => $setup_guide_url,
			'adminUrl'      => $admin_url,
		];
	}

	/**
	 * Disable Admin notices for this page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_admin_notices(): void {
		add_action(
			'admin_init',
			function(): void {
				if ( ! $this->is_onboarding_page() ) {
					return;
				}

				remove_all_actions( 'admin_notices' );
			}
		);
	}

	/**
	 * Get the current Onboarding Step (key).
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_current_step_key(): string {
		$step = false;

		if ( ! empty( $_GET[ self::STEP_PARAM ] ) ) { // phpcs:ignore
			$step = sanitize_text_field( wp_unslash( $_GET[ self::STEP_PARAM ] ) ); // phpcs:ignore
		} elseif ( $this->is_onboarding_page() ) {
			$step = array_key_first( $this->steps );
		}

		// Default to first step.
		if ( ! $step || ! array_key_exists( $step, $this->steps ) ) {
			$step = get_transient( self::STEP_TRANSIENT );
		}

		// Fallback on first step.
		if ( ! $step ) {
			$step = array_key_first( $this->steps );
		}

		$this->set_step_transient( $step );

		return $step;
	}

	/**
	 * Get the current step array.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_current_step(): array {
		return $this->steps[ $this->get_current_step_key() ];
	}

	/**
	 * Get the next step.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	private function get_next_step_key(): ?string {
		$step = $this->get_current_step_key();
		$next = false;

		foreach ( $this->steps as $step_id => $step_data ) {
			if ( $step_id === $step ) {
				$next = true;
				continue;
			}

			if ( $next ) {
				return $step_id;
			}
		}

		return null;
	}

	/**
	 * Check if the current step is the last step.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_last_step(): bool {
		$current = $this->get_current_step_key();
		$last    = array_key_last( $this->steps );
		return $current && $current === $last;
	}

	/**
	 * Check if we are mid-onboarding
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_mid_onboarding(): bool {
		if ( ! is_admin() ) {
			return false;
		}

		if ( ! goodbids()->network->nonprofits->is_onboarded() ) {
			return true;
		}

		if ( ! empty( $_GET[ self::IS_ONBOARDING_PARAM ] ) ) { // phpcs:ignore
			return true;
		}

		if ( get_transient( self::STEP_TRANSIENT ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Redirect back to the onboarding Payments page after setting up WooCommerce
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function onboarding_redirect(): void {
		add_action(
			'admin_init',
			function () {
				if ( ! $this->is_onboarding_page() || wp_doing_ajax() || goodbids()->network->nonprofits->is_onboarded() ) {
					return;
				}

				$step = $this->get_current_step();

				if ( $step['is_complete'] && $this->get_next_step_key() ) {
					wp_safe_redirect( $this->get_url( $this->get_next_step_key() ) );
					exit;
				}
			},
			30
		);

		// Perform the Redirect to the next step.
		add_action(
			'admin_init',
			function () {
				if ( wp_doing_ajax() || $this->is_onboarding_page() || ! $this->is_mid_onboarding() || goodbids()->network->nonprofits->is_onboarded() ) {
					return;
				}

				// Don't redirect if we are on supported pages
				$current_step = $this->get_current_step();

				if ( $current_step['is_step_page'] && ! $current_step['is_complete'] ) {
					return;
				}

				if ( ! empty( $current['callback'] ) && is_callable( $current['callback'] ) ) {
					call_user_func( $current['callback'] );
				}

				// We're done here.
				if ( ! $this->get_next_step_key() ) {
					return;
				}

				$redirect = $this->get_url( $this->get_next_step_key() );
				wp_safe_redirect( $redirect );
				exit;
			},
			50
		);

		add_action(
			'admin_footer',
			function () {
				if ( goodbids()->network->nonprofits->is_onboarded() || ! $this->is_onboarding_page() || wp_doing_ajax() || ! $this->is_last_step() ) {
					return;
				}

				$this->mark_onboarding_completed();
			}
		);
	}

	/**
	 * Set the Current Step
	 *
	 * @since 1.0.0
	 *
	 * @param string $step
	 *
	 * @return void
	 */
	private function set_step_transient( string $step ): void {
		set_transient( self::STEP_TRANSIENT, $step );
	}

	/**
	 * Check if we are on the WooCommerce onboarding page
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_wc_onboarding_page(): bool {
		if ( ! is_admin() ) {
			return false;
		}

		global $pagenow;

		return 'admin.php' === $pagenow && ! empty( $_GET['page'] ) && 'wc-admin' === $_GET['page'] && ! empty( $_GET['path'] ) && '/setup-wizard' === $_GET['path']; // phpcs:ignore
	}

	/**
	 * Check if we are on the WooCommerce Admin page
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_wc_admin_page(): bool {
		if ( $this->is_wc_onboarding_page() ) {
			return false;
		}

		global $pagenow;

		return 'admin.php' === $pagenow && ! empty( $_GET['page'] ) && 'wc-admin' === $_GET['page'] && empty( $_GET['path'] ); // phpcs:ignore
	}

	/**
	 * Mark WC Onboarding as Skipped.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function mark_wc_onboarding_skipped(): void {
		$profile = get_option( 'woocommerce_onboarding_profile', [] );

		if ( ! isset( $profile['skipped'] ) ) {
			$profile['skipped'] = true;
			update_option( 'woocommerce_onboarding_profile', $profile );
		}
	}

	/**
	 * Check if WooCommerce onboarding is completed
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function completed_wc_onboarding(): bool {
		if ( ! class_exists( 'Automattic\WooCommerce\Internal\Admin\Onboarding\OnboardingProfile' ) ) {
			require_once dirname( WC_PLUGIN_FILE ) . '/src/Admin/API/OnboardingProfile.php';
		}

		return ! OnboardingProfile::needs_completion();
	}

	/**
	 * Change the text of the WooCommerce onboarding button
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function woocommerce_adjustments(): void {
		add_action(
			'admin_footer',
			function() {
				if ( ! $this->is_wc_onboarding_page() || ! $this->is_mid_onboarding() ) {
					return;
				}

				$text = __( 'Continue with Onboarding', 'goodbids' );
				?>
				<script>
					jQuery( function( $ ) {
						const gbOnboardingInterval = setInterval(
							function () {
								const $target = $( '.woocommerce-profiler-go-to-mystore__button-container button' ),
									newText = '<?php echo esc_js( $text ); ?>';

								if ( $target.length && newText !== $target.text() ) {
									$target.text( newText );
									clearInterval( gbOnboardingInterval );
								}
							},
							100
						);
					} );
				</script>
				<?php
			}
		);
	}

	/**
	 * Check if we are on the Stripe Payments setup page
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_stripe_page(): bool {
		global $pagenow;

		if ( ! is_admin() ) {
			return false;
		}

		if ( isset( $_GET['wcs_stripe_code'], $_GET['wcs_stripe_state'] ) ) { // phpcs:ignore
			return true;
		}

		if ( 'admin.php' !== $pagenow || empty( $_GET['page'] ) || 'wc-settings' !== $_GET['page'] ) { // phpcs:ignore
			return false;
		}

		return ! empty( $_GET['section'] ) && 'stripe' === $_GET['section']; // phpcs:ignore
	}

	/**
	 * Set up JS redirects connected to the Stripe Setup Modal
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function stripe_adjustments(): void {
		add_action(
			'admin_footer',
			function () {
				if ( ! $this->is_stripe_page() || ! $this->is_mid_onboarding() ) {
					return;
				}

				?>
				<style>
					.woocommerce #mainform .nav-tab-wrapper,
					.notice.wcs-nux__notice,
					h2 .wc-admin-breadcrumb {
						display: none;
					}
				</style>
				<?php

				$redirect = $this->get_url( $this->get_next_step_key() );

				if ( ! $redirect ) {
					return;
				}

				$redirect = esc_js( $redirect );
				$redirect = str_replace( '&amp;', '&', $redirect );
				?>
				<script>
					jQuery( function( $ ) {
						const gbStripeCloseInterval = setInterval(
							function () {
								const $button = $( '.components-modal__header button' );

								if ( $button.length ) {
									clearInterval( gbStripeCloseInterval );

									$button.on(
										'click',
										function ( e ) {
											e.preventDefault();
											window.location.href = '<?php echo $redirect; // phpcs:ignore ?>';
											return false;
										}
									);
								}
							},
							100
						);

						let gbStripeModalOpened = false;
						const gbStripeModalInterval = setInterval(
							function () {
								const $modal = $( '.wcstripe-confirmation-modal' );

								if ( $modal.length ) {
									if ( ! gbStripeModalOpened ) { // Modal was opened.
										gbStripeModalOpened = true;
									}
								} else {
									if ( gbStripeModalOpened ) { // Modal was closed automatically.
										clearInterval( gbStripeModalInterval );
										window.location.href = '<?php echo $redirect; // phpcs:ignore ?>';
										return false;
									}
								}
							},
							100
						);
					} );
				</script>
				<?php
			}
		);
	}

	/**
	 * Check if Payments Onboarding is completed
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function completed_payments_onboarding(): bool {
		return count( WC()->payment_gateways()->get_available_payment_gateways() ) > 0;
	}

	/**
	 * Check if Accessibility Checker License is completed
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function completed_accessibility_license(): bool {
		return ( defined( 'EDACP_KEY_VALID' ) && true === EDACP_KEY_VALID ) || 'valid' === get_option( 'edacp_license_status' );
	}

	/**
	 * Maybe Mark Onboarding as Completed.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_mark_onboarding_completed(): void {
		$maybe = function() {
			if ( wp_doing_ajax() || empty( $_GET[ self::DONE_ONBOARDING_PARAM ] ) ) { // phpcs:ignore
				return;
			}

			$this->mark_onboarding_completed();

			$redirect = remove_query_arg( self::DONE_ONBOARDING_PARAM );
			wp_safe_redirect( $redirect );
			exit;
		};

		add_action( 'init', $maybe );
		add_action( 'admin_init', $maybe );
	}

	/**
	 * Mark Onboarding as Completed.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function mark_onboarding_completed(): void {
		delete_transient( self::STEP_TRANSIENT );

		// Make sure Onboarding isn't already marked as completed.
		if ( ! goodbids()->network->nonprofits->is_onboarded() ) {
			update_option( 'goodbids_onboarded', current_time( 'mysql' ) );

			/**
			 * Action after Onboarding has completed
			 * @since 1.0.0
			 * @param int $blog_id The blog ID.
			 */
			do_action( 'goodbids_onboarding_completed', get_current_blog_id() );
		}
	}
}
