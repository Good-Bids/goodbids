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
	const REDIRECT_TRANSIENT = 'gb-onboarding-redirect';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_TRANSIENT = 'gb-onboarding-step';

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
	const ONBOARDING_STEPS = [
		self::STEP_CREATE_STORE,
		self::STEP_SET_UP_PAYMENTS,
		self::STEP_ACCESSIBILITY_CHECKER_LICENSE,
		self::STEP_ONBOARDING_COMPLETE,
	];

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( is_main_site() || is_network_admin() ) {
			return;
		}

		// Redirect to Onboarding page if not yet onboarded.
		$this->force_setup();

		// Remove any admin notices for this page.
		$this->disable_admin_notices();

		// Add the menu page for the setup dashboard
		$this->add_menu_dashboard_page();

		// Enqueue Scripts
		$this->enqueue_scripts();

		// Perform redirects as needed
		$this->redirect_current_step();
		$this->wc_onboarding_redirect();
		$this->payments_redirect();
		$this->accessibility_redirect();

		// Customize the WooCommerce onboarding button
		$this->customize_wc_onboarding_button();

		// Remove the WooCommerce Settings Tabs when setting up Stripe
		$this->hide_woocommerce_settings_tabs();

		// Flag Onboarding as Completed.
		$this->mark_onboarding_completed();
	}

	/**
	 * Require Setup
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function force_setup(): void {
		add_action(
			'current_screen',
			function () {
				if ( goodbids()->network->nonprofits->is_onboarded() || $this->is_onboarding_page() || is_super_admin() ) {
					return;
				}

				$screen      = get_current_screen();
				$setup_pages = [
					'options-general',
					'woocommerce_page_wc-admin',
					'woocommerce_page_wc-settings',
				];

				if ( in_array( $screen->id, $setup_pages, true ) ) {
					return;
				}

				wp_safe_redirect( admin_url( 'admin.php?page=' . self::PAGE_SLUG ) );
				exit;
			}
		);

		add_action(
			'admin_menu',
			function () {
				if ( goodbids()->network->nonprofits->is_onboarded() || is_super_admin() ) {
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
					if ( $this->is_onboarding_page() ) {
						wp_safe_redirect( admin_url() );
						exit;
					}
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
	 * Redirect to current step if they click on the Onboarding menu item.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function redirect_current_step(): void {
		add_action(
			'admin_init',
			function () {
				if ( ! $this->is_onboarding_page() ) {
					return;
				}

				if ( self::STEP_CREATE_STORE === $this->get_current_step() && $this->completed_wc_onboarding() ) {
					set_transient( self::STEP_TRANSIENT, self::STEP_SET_UP_PAYMENTS );
					wp_safe_redirect( $this->get_url( self::STEP_SET_UP_PAYMENTS ) );
					exit;
				}

				if ( self::STEP_SET_UP_PAYMENTS === $this->get_current_step() && $this->completed_payments_onboarding() ) {
					set_transient( self::STEP_TRANSIENT, self::STEP_ACCESSIBILITY_CHECKER_LICENSE );
					wp_safe_redirect( $this->get_url( self::STEP_ACCESSIBILITY_CHECKER_LICENSE ) );
					exit;
				}

				if ( self::STEP_ACCESSIBILITY_CHECKER_LICENSE === $this->get_current_step() && $this->completed_accessibility_license() ) {
					set_transient( self::STEP_TRANSIENT, self::STEP_ONBOARDING_COMPLETE );
					wp_safe_redirect( $this->get_url( self::STEP_ONBOARDING_COMPLETE ) );
					exit;
				}

				// Make sure they're not jumping ahead.
				if ( self::STEP_ONBOARDING_COMPLETE === $this->get_current_step() ) {
					if ( ! $this->completed_wc_onboarding() ) {
						set_transient( self::STEP_TRANSIENT, self::STEP_CREATE_STORE );
						wp_safe_redirect( $this->get_url( self::STEP_CREATE_STORE ) );
						exit;
					}

					if ( ! $this->completed_payments_onboarding() ) {
						set_transient( self::STEP_TRANSIENT, self::STEP_SET_UP_PAYMENTS );
						wp_safe_redirect( $this->get_url( self::STEP_SET_UP_PAYMENTS ) );
						exit;
					}

					if ( ! $this->completed_accessibility_license() ) {
						set_transient( self::STEP_TRANSIENT, self::STEP_ACCESSIBILITY_CHECKER_LICENSE );
						wp_safe_redirect( $this->get_url( self::STEP_ACCESSIBILITY_CHECKER_LICENSE ) );
						exit;
					}
				}

				$step = get_transient( self::STEP_TRANSIENT );

				if ( $step && $step !== $this->get_current_step() ) {
					delete_transient( self::STEP_TRANSIENT );
					wp_safe_redirect( $this->get_url( $step ) );
					exit;
				}
			}
		);

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

		// Setup Guide URL
		$setup_guide_url = admin_url( 'admin.php?page=' . Guide::PAGE_SLUG );
		$setup_guide_url = add_query_arg( self::DONE_ONBOARDING_PARAM, 1, $setup_guide_url );

		// Admin URL
		$admin_url = admin_url();
		$admin_url = add_query_arg( self::DONE_ONBOARDING_PARAM, 1, $admin_url );

		return [
			'appID'                   => self::PAGE_SLUG,
			'stepParam'               => self::STEP_PARAM,
			'stepOptions'             => self::ONBOARDING_STEPS,
			'createStoreUrl'          => $create_store_url,
			'setUpPaymentsUrl'        => $payments_url,
			'onboardingCompleteUrl'   => $onboarding_complete_url,
			'accessibilityCheckerUrl' => $accessibility_checker_url,
			'setupGuideUrl'           => $setup_guide_url,
			'adminUrl'                => $admin_url,
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
	 * Get the current Onboarding Step.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_current_step(): string {
		if ( ! empty( $_GET[ self::STEP_PARAM ] ) ) { // phpcs:ignore
			$step = sanitize_text_field( wp_unslash( $_GET[ self::STEP_PARAM ] ) ); // phpcs:ignore
			set_transient( self::STEP_TRANSIENT, $step );
			return $step;
		}

		return self::STEP_CREATE_STORE;
	}

	/**
	 * Check if we are mid-onboarding
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_mid_onboarding(): bool {
		if ( ! is_admin() || goodbids()->network->nonprofits->is_onboarded() ) {
			return false;
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

		if ( ! OnboardingProfile::needs_completion() ) {
			return false;
		}

		$profile = get_option( 'woocommerce_onboarding_profile', [] );

		if ( ! isset( $profile['skipped'] ) ) {
			$profile['skipped'] = true;
			update_option( 'woocommerce_onboarding_profile', $profile );
		}

		return true;
	}

	/**
	 * Change the text of the WooCommerce onboarding button
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function customize_wc_onboarding_button(): void {
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
	 * Redirect back to the onboarding Payments page after setting up WooCommerce
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function wc_onboarding_redirect(): void {
		add_action(
			'admin_init',
			function () {
				$transient = get_transient( self::REDIRECT_TRANSIENT );

				if ( ! $transient ) {
					return;
				}

				// Don't redirect if we are on supported pages
				if ( $this->is_wc_onboarding_page() && $this->is_mid_onboarding() ) {
					return;
				}

				delete_transient( self::REDIRECT_TRANSIENT );
				wp_safe_redirect( $transient );
				exit;
			}
		);

		// Redirect to the next step after the store setup is completed.
		add_action(
			'admin_init',
			function () {
				if ( ! $this->is_wc_onboarding_page() || ! $this->is_mid_onboarding() ) {
					return;
				}

				set_transient( self::REDIRECT_TRANSIENT, $this->get_url( self::STEP_SET_UP_PAYMENTS ) );
			},
			50
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
		if ( ! is_admin() ) {
			return false;
		}

		global $pagenow;

		if ( 'admin.php' !== $pagenow || empty( $_GET['page'] ) || 'wc-settings' !== $_GET['page'] ) { // phpcs:ignore
			return false;
		}

		return ! empty( $_GET['section'] ) && 'stripe' === $_GET['section']; // phpcs:ignore
	}

	/**
	 * Redirect back to the onboarding Finalize page after setting up Stripe
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function payments_redirect(): void {
		add_action(
			'admin_init',
			function () {
				$transient = get_transient( self::REDIRECT_TRANSIENT );

				if ( ! $transient ) {
					return;
				}

				// Don't redirect if we are on supported pages
				if ( $this->is_stripe_page() && $this->is_mid_onboarding() && ! $this->completed_payments_onboarding() ) {
					return;
				}

				// Wait until these are removed before performing our redirect.
				if ( isset( $_GET['wcs_stripe_code'], $_GET['wcs_stripe_state'] ) ) { // phpcs:ignore
					return;
				}

				delete_transient( self::REDIRECT_TRANSIENT );
				wp_safe_redirect( $transient );
				exit;
			},
			50
		);

		// Set the redirect to the final step after the payments setup is completed.
		add_action(
			'admin_init',
			function () {
				if ( ! $this->is_stripe_page() || ! $this->is_mid_onboarding() ) {
					return;
				}

				set_transient( self::REDIRECT_TRANSIENT, $this->get_url( self::STEP_ACCESSIBILITY_CHECKER_LICENSE ) );
			},
			50
		);

		add_action(
			'admin_footer',
			function () {
				if ( ! $this->is_stripe_page() || ! $this->is_mid_onboarding() ) {
					return;
				}

				$redirect = get_transient( self::REDIRECT_TRANSIENT );

				if ( ! $redirect ) {
					return;
				}
				?>
				<script>
					jQuery( function( $ ) {
						const gbStripeCloseInterval = setInterval(
							function () {
								const $button = $( '.components-modal__header button' );

								if ( $button.length ) {
									clearInterval( gbOnboardingInterval );

									$button.on(
										'click',
										function ( e ) {
											e.preventDefault();
											window.location.href = '<?php echo esc_js( $redirect ); ?>';
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
										window.location.href = '<?php echo esc_js( $redirect ); ?>';
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
	 * Hide WooCommerce settings tabs.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function hide_woocommerce_settings_tabs(): void {
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
	 * Redirect to the next step after the accessibility license setup is completed.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function accessibility_redirect(): void {
		add_action(
			'admin_init',
			function () {
				$transient = get_transient( self::REDIRECT_TRANSIENT );

				if ( ! $transient ) {
					return;
				}

				// Don't redirect if we are on supported pages
				if ( goodbids()->accessibility->is_license_page() && $this->is_mid_onboarding() ) {
					return;
				}

				delete_transient( self::REDIRECT_TRANSIENT );
				wp_safe_redirect( $transient );
				exit;
			}
		);

		// Set the redirect when on the Accessibility Checker License page.
		add_action(
			'admin_init',
			function () {
				if ( ! goodbids()->accessibility->is_license_page() || ! $this->is_mid_onboarding() ) {
					return;
				}

				set_transient( self::REDIRECT_TRANSIENT, $this->get_url( self::STEP_ONBOARDING_COMPLETE ) );
			},
			50
		);
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
	 * Mark Onboarding as Completed.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function mark_onboarding_completed(): void {
		$mark_completed = function () {
			if ( empty( $_GET[ self::DONE_ONBOARDING_PARAM ] ) ) { // phpcs:ignore
				return;
			}

			delete_transient( self::STEP_TRANSIENT );
			delete_transient( self::REDIRECT_TRANSIENT );

			// Make sure Onboarding isn't already marked as completed.
			if ( ! goodbids()->network->nonprofits->is_onboarded() ) {
				update_option( 'goodbids_onboarded', current_time( 'mysql' ) );
			}

			/**
			 * Action after Onboarding has completed
			 * @since 1.0.0
			 * @param int $blog_id The blog ID.
			 */
			do_action( 'goodbids_onboarding_completed', get_current_blog_id() );

			$redirect = remove_query_arg( self::DONE_ONBOARDING_PARAM );
			wp_safe_redirect( $redirect );
			exit;
		};

		add_action( 'init', $mark_completed );
		add_action( 'admin_init', $mark_completed );
	}
}
