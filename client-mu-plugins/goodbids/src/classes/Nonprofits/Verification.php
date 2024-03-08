<?php
/**
 * GoodBids Nonprofit Verification
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use GoodBids\Network\Nonprofit;
use GoodBids\Utilities\Log;

/**
 * Verification Class
 *
 * @since 1.0.0
 */
class Verification {

	/**
	 * Details/Verification Page Slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'goodbids-site-details';

	/**
	 * Details/Verification Parent Page
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PARENT_PAGE = 'site-settings.php';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const OPTION_SLUG = 'gbnp';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STATUS_OPTION = 'status';

	/**
	 * Nonprofit custom fields
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private array $custom_fields = [];

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Adjust custom Nonprofit fields.
		$this->manipulate_custom_fields();

		// Add Details/Verification Site Tab
		$this->add_custom_site_tab();

		// Add Details/Verification Submenu Page
		$this->add_submenu_page();

		// Adjust Site Actions
		$this->adjust_site_actions();

		// Save Nonprofit Data, if applicable.
		$this->maybe_save_nonprofit_data();

		// Disable Admin for Unverified Sites
		$this->disable_admin_for_unverified();

		// Allow Nonprofit Site settings to override default config.
		$this->allow_nonprofit_config_override();
	}

	/**
	 * Add a Custom Tab under Edit Site for the Details/Verification Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_custom_site_tab(): void {
		add_filter(
			'network_edit_site_nav_links',
			function ( array $links ): array {
				// Don't use get_site_id() here.
				$site_id = intval( sanitize_text_field( wp_unslash( $_GET['id'] ) ) ); // phpcs:ignore

				if ( get_main_site_id() === $site_id ) {
					return $links;
				}

				$label = ! $this->is_verified( $site_id ) ? __( 'Verification', 'goodbids' ) : __( 'Details', 'goodbids' );

				$links[ self::PAGE_SLUG ] = [
					'label' => $label,
					'url'   => self:: PARENT_PAGE . '?page=' . self::PAGE_SLUG,
					'cap'   => 'manage_sites',
				];

				return $links;
			}
		);
	}

	/**
	 * Add Submenu Page for Details/Verification
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_submenu_page(): void {
		add_action(
			'network_admin_menu',
			function (): void {
				$site_id = ! empty( $_GET['id'] ) ? intval( sanitize_text_field( wp_unslash( $_GET['id'] ) ) ) : null; // phpcs:ignore

				if ( ! $site_id ) {
					return;
				}

				$title = $this->is_verified( $site_id ) ? __( 'Nonprofit Details', 'goodbids' ) : __( 'Nonprofit Verification', 'goodbids' );

				add_submenu_page(
					self::PARENT_PAGE,
					$title,
					$title,
					'manage_sites',
					self::PAGE_SLUG,
					[ $this, 'details_page' ]
				);
			}
		);

	}

	/**
	 * Render the Verification/Details Admin Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function details_page(): void {
		global $pagenow;

		// Change pagenow so that the correct menu is highlighted.
		$pagenow = self::PAGE_SLUG; // phpcs:ignore
		$site_id = $this->get_site_id();

		$site_name = get_blog_details( $site_id )->blogname;
		$verified  = $this->is_verified( $site_id );
		$disabled  = $this->form_is_disabled( $site_id );
		$data      = $this->get_nonprofit_data( $site_id );
		$fields    = $this->get_custom_fields();
		$prefix    = self::OPTION_SLUG;
		$page_slug = self::PAGE_SLUG;

		if ( ! empty( $_POST ) ) { // phpcs:ignore
			$data = array_merge( $data, $_POST[ self::OPTION_SLUG ] ); // phpcs:ignore
		}

		if ( $disabled ) {
			foreach ( $fields as $key => $field ) {
				$fields[ $key ]['disabled'] = true;
			}
		}

		$vars = compact(
			'data',
			'fields',
			'prefix',
			'verified',
			'disabled',
			'site_id',
			'site_name',
			'page_slug'
		);

		goodbids()->load_view(
			'network/nonprofit-details.php',
			$vars
		);
	}

	/**
	 * Check if the Form should be Disabled
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return bool
	 */
	private function form_is_disabled( int $site_id ): bool {
		return ! is_super_admin() && $this->is_verified( $site_id );
	}

	/**
	 * Add Verification to the Sites List
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function adjust_site_actions(): void {
		add_filter(
			'manage_sites_action_links',
			function ( array $actions, int $blog_id ): array {
				if ( get_main_site_id() === $blog_id ) {
					return $actions;
				}

				// Remove Spam action.
				unset( $actions['spam'] );

				$page = self::PAGE_SLUG;
				$url  = add_query_arg( 'page', $page, network_admin_url( self::PARENT_PAGE ) );
				$url  = add_query_arg( 'id', $blog_id, $url );

				if ( ! $this->is_verified( $blog_id ) ) {
					// Adjust Edit Link.
					$actions['edit'] = sprintf(
						'<a href="%s">%s</a>',
						esc_url( $url ),
						__( 'Verification', 'goodbids' )
					);

					// Remove other actions until verified.
					if ( ! is_super_admin() ) {
						unset( $actions['visit'] );
						unset( $actions['backend'] );
					}
				}

				$actions['details'] = sprintf(
					'<a href="%s">%s</a>',
					esc_url( $url ),
					__( 'Details', 'goodbids' )
				);

				return $actions;
			},
			10,
			2
		);
	}

	/**
	 * Check if a Site is Verified
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return bool
	 */
	public function is_verified( int $site_id ): bool {
		if ( get_main_site_id() === $site_id ) {
			return true;
		}

		return boolval( $this->get_nonprofit_data( $site_id, 'verification', 'read' ) );
	}

	/**
	 * Disable fields and add Super Admin Fields
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function manipulate_custom_fields(): void {
		add_filter(
			'goodbids_nonprofit_custom_fields',
			function ( array $fields, string $context ): array {
				if ( 'edit' === $context && function_exists( 'wp_get_current_user' ) && ! is_super_admin() ) {
					return $fields;
				}

				$fields['sep_verification']                   = [
					'type' => 'separator',
				];
				$fields['verification']                       = [
					'label'       => __( 'Verified', 'goodbids' ),
					'type'        => 'toggle',
					'default'     => '',
					'placeholder' => '',
					'description' => __( 'Only visible to Super Admins', 'goodbids' ),
				];
				$fields['sep_invoices']                       = [
					'type' => 'separator',
				];
				$fields['config:invoices.payment-terms-days'] = [
					'label'       => __( 'Net Payment Terms', 'goodbids' ),
					'type'        => 'number',
					'class'       => 'small-text',
					'default'     => '',
					'after'       => '<span>' . __( 'days', 'goodbids' ) . '</span>',
					'placeholder' => goodbids()->get_config( 'invoices.payment-terms-days', false ),
					'description' => __( 'Number of days until an invoice is due.', 'goodbids' ),
				];
				$fields['stripe_data']                        = [
					'label'    => __( 'Stripe Details', 'goodbids' ),
					'type'     => 'html',
					'callback' => [ $this, 'stripe_data_field_callback' ],
				];

				return $fields;
			},
			10,
			2
		);
	}

	/**
	 * Initialize Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_custom_fields(): void {
		$this->custom_fields = [
			'legal-name'          => [
				'label'       => __( 'Nonprofit Legal Name', 'goodbids' ),
				'type'        => 'text',
				'default'     => '',
				'placeholder' => '',
				'required'    => true,
			],
			'ein'                 => [
				'label'       => __( 'Nonprofit EIN', 'goodbids' ),
				'type'        => 'text',
				'default'     => '',
				'placeholder' => 'XX-XXXXXXX',
				'required'    => true,
			],
			'website'              => [
				'label'       => __( 'Nonprofit Website', 'goodbids' ),
				'type'        => 'url',
				'default'     => '',
				'placeholder' => 'https://',
				'required'    => true,
			],
			self::STATUS_OPTION    => [
				'label'       => __( 'Site Status', 'goodbids' ),
				'type'        => 'select',
				'default'     => Nonprofit::STATUS_PENDING,
				'placeholder' => '',
				'required'    => true,
				'description' => __( 'This determines if the site is accessible from the front-end', 'goodbids' ),
				'options'     => [
					[
						'label' => __( 'Pending', 'goodbids' ),
						'value' => Nonprofit::STATUS_PENDING,
					],
					[
						'label' => __( 'Live', 'goodbids' ),
						'value' => Nonprofit::STATUS_LIVE,
					],
					[
						'label' => __( 'Inactive', 'goodbids' ),
						'value' => Nonprofit::STATUS_INACTIVE,
					],
				],
			],
			'sep1'                  => [
				'type' => 'separator',
			],
			'primary_contact_name'  => [
				'label'       => __( 'Primary Contact Legal Name', 'goodbids' ),
				'type'        => 'text',
				'default'     => '',
				'placeholder' => '',
				'required'    => true,
			],
			'primary_contact_email' => [
				'label'       => __( 'Primary Contact Email Address', 'goodbids' ),
				'type'        => 'email',
				'default'     => '',
				'placeholder' => 'email@domain.com',
				'required'    => true,
				'on_update'   => [ $this, 'update_stripe_email' ],
			],
			'primary_contact_title' => [
				'label'       => __( 'Primary Contact Job Title', 'goodbids' ),
				'type'        => 'text',
				'default'     => '',
				'placeholder' => '',
				'required'    => true,
			],
			'impact_vertical'       => [
				'label'       => __( 'Impact Vertical', 'goodbids' ),
				'type'        => 'select',
				'default'     => '',
				'placeholder' => '',
				'required'    => true,
				'options'     => [
					__( 'Animals', 'goodbids' ),
					__( 'Arts & Culture', 'goodbids' ),
					__( 'Civic & Community', 'goodbids' ),
					__( 'Disaster & Emergency Services', 'goodbids' ),
					__( 'Diversity, Equity & Inclusion', 'goodbids' ),
					__( 'Education/School', 'goodbids' ),
					__( 'Environment & Sustainability', 'goodbids' ),
					__( 'Faith-based & Religious', 'goodbids' ),
					__( 'Food Insecurity', 'goodbids' ),
					__( 'Health and Wellness', 'goodbids' ),
					__( 'Housing & Homelessness', 'goodbids' ),
					__( 'Humanities/International Affairs', 'goodbids' ),
					__( 'Justice & Legal Services', 'goodbids' ),
					__( 'Reproductive Rights', 'goodbids' ),
					__( 'Research', 'goodbids' ),
					__( 'Sports and Recreation', 'goodbids' ),
					__( 'Technology', 'goodbids' ),
					__( 'Veterans Services', 'goodbids' ),
					__( 'Other', 'goodbids' ),
				],
			],
			'finance_contact_name'  => [
				'label'       => __( 'Finance Contact Legal Name', 'goodbids' ),
				'type'        => 'text',
				'default'     => '',
				'placeholder' => '',
			],
			'finance_contact_email' => [
				'label'       => __( 'Finance Contact Email Address', 'goodbids' ),
				'type'        => 'email',
				'default'     => '',
				'placeholder' => 'email@domain.com',
				'on_update'   => [ $this, 'update_stripe_email' ],
			],
			'finance_contact_title' => [
				'label'       => __( 'Finance Contact Job Title', 'goodbids' ),
				'type'        => 'text',
				'default'     => '',
				'placeholder' => '',
			],
		];
	}

	/**
	 * Retrieve array of Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context The context of the fields.
	 *
	 * @return array
	 */
	public function get_custom_fields( string $context = 'edit' ): array {
		if ( empty( $this->custom_fields ) ) {
			$this->init_custom_fields();
		}

		/**
		 * Filter the Nonprofit custom fields.
		 * @since 1.0.0
		 * @param array $fields The custom fields array
		 * @param string $context Whether we're using this for editing.
		 */
		return apply_filters( 'goodbids_nonprofit_custom_fields', $this->custom_fields, $context );
	}

	/**
	 * Validate Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function validate_custom_fields(): bool {
		$site_id = $this->get_site_id();

		if ( ! $site_id || empty( $_POST[ self::OPTION_SLUG ] ) ) {
			return false;
		}

		check_admin_referer( 'gb-site-custom-data', '_wpnonce_gb-site-custom-data' );

		// Grab our nonprofit data.
		$data = $_POST[ self::OPTION_SLUG ]; // phpcs:ignore

		// Validate required fields.
		foreach ( $this->get_custom_fields() as $key => $field ) {
			if ( empty( $field['required'] ) || true !== $field['required'] ) {
				continue;
			}

			if ( empty( $data[ $key ] ) ) {
				goodbids()->utilities->display_admin_error(
					sprintf(
						'%s %s',
						esc_html( $field['label'] ),
						esc_html__( 'is a required field.', 'goodbids' )
					),
					true,
					true
				);
				return false;
			}
		}

		// Validate EIN as 9 digits.
		if ( ! empty( $data['ein'] ) ) {
			$ein = preg_replace( '/[^0-9]/', '', $data['ein'] );

			if ( 9 !== strlen( $ein ) ) {
				goodbids()->utilities->display_admin_error(
					esc_html__( 'Nonprofit EIN must include 9 digits. (##-#######)', 'goodbids' ),
					true,
					true
				);
				return false;
			}
		}

		// Validate Website as URL.
		if ( ! empty( $data['website'] ) ) {
			if ( ! filter_var( $data['website'], FILTER_VALIDATE_URL ) ) {
				goodbids()->utilities->display_admin_error(
					esc_html__( 'Nonprofit Website must be a valid URL. Be sure to include "https://".', 'goodbids' ),
					true,
					true
				);
				return false;
			}
		}

		return true;
	}

	/**
	 * Retrieve Nonprofit custom field data.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $site_id  Site ID.
	 * @param string $field_id The Field ID to retrieve. Default empty.
	 * @param string $context  The context for retrieving the data.
	 *
	 * @return mixed
	 */
	public function get_nonprofit_data( ?int $site_id, string $field_id = '', string $context = 'edit' ): mixed {
		$data = [];

		foreach ( $this->get_custom_fields( $context ) as $key => $field ) {
			if ( 'separator' === $field['type'] ) {
				continue;
			}

			$field_key   = self::OPTION_SLUG . '-' . $key;
			$field_value = get_site_meta( $site_id, $field_key, true );

			if ( ! $field_value && isset( $field['default'] ) ) {
				$field_value = $field['default'];
			}

			$data[ $key ] = $field_value;
		}

		if ( ! empty( $field_id ) ) {
			if ( isset( $data[ $field_id ] ) ) {
				return $data[ $field_id ];
			}

			return null;
		}

		return $data;
	}

	/**
	 * Save Nonprofit Data
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_save_nonprofit_data(): void {
		add_action(
			'admin_init',
			function () {
				if ( wp_doing_ajax() ) {
					return;
				}

				$site_id = $this->get_site_id();

				if ( ! $site_id || empty( $_POST[ self::OPTION_SLUG ] ) ) { // phpcs:ignore
					return;
				}

				if ( $this->form_is_disabled( $site_id ) ) {
					return;
				}

				if ( ! $this->validate_custom_fields() ) {
					return;
				}

				$data     = $_POST[ self::OPTION_SLUG ]; // phpcs:ignore
				$verified = false;

				foreach ( $this->get_custom_fields( 'save' ) as $key => $field ) {
					$field['field_id'] = $key;

					$value      = ! isset( $data[ $key ] ) ? '' : $data[ $key ];
					$meta_key   = self::OPTION_SLUG . '-' . $key;
					$meta_value = sanitize_text_field( $value );

					$meta_key    = self::OPTION_SLUG . '-' . $key;
					$meta_value  = sanitize_text_field( $data[ $key ] );

					if ( 'verification' === $key ) {
						$meta_value = $meta_value ? current_time( 'mysql', true ) : '';
						$verified   = boolval( $meta_value );
					}

					$prev_value = get_site_meta( $site_id, $meta_key, true );

					update_site_meta( $site_id, $meta_key, $meta_value );

					if ( ! empty( $field['on_update'] ) && is_callable( $field['on_update'] ) ) {
						/**
						 * Fires after a Nonprofit custom field has been updated.
						 *
						 * @since 1.0.0
						 *
						 * @param mixed $meta_value The new meta value.
						 * @param mixed $prev_value The previous meta value.
						 * @param string $meta_key The meta key.
						 * @param int $site_id The site ID.
						 * @param array $field The field data.
						 */
						call_user_func( $field['on_update'], $meta_value, $prev_value, $meta_key, $site_id, $field );
					}
				}

				if ( $verified ) {
					goodbids()->sites->swap(
						fn () => do_action( 'goodbids_nonprofit_verified', $site_id ),
						$site_id
					);
				}

				goodbids()->utilities->display_admin_success( __( 'Nonprofit data has been updated.', 'goodbids' ), true, true );
			}
		);
	}

	/**
	 * Check if we're on the Site Details/Verification Page.
	 *
	 * @since 1.0.0
	 *
	 * @return bool|int
	 */
	private function get_site_id(): bool|int {
		global $pagenow;

		if ( ! in_array( $pagenow, [ self::PARENT_PAGE, self::PAGE_SLUG ] ) || empty( $_GET['page'] ) || empty( $_GET['id'] ) ) { // phpcs:ignore
			return false;
		}

		$page = sanitize_text_field( wp_unslash( $_GET['page'] ) ); // phpcs:ignore

		if ( self::PAGE_SLUG !== $page ) {
			return false;
		}

		$site_id = intval( sanitize_text_field( wp_unslash( $_GET['id'] ) ) ); // phpcs:ignore

		if ( ! $site_id ) {
			return false;
		}

		return $site_id;
	}

	/**
	 * Disable Admin for Unverified Sites
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_admin_for_unverified(): void {
		add_action(
			'admin_init',
			function (): void {
				if ( is_network_admin() || ! is_admin() || wp_doing_ajax() ) {
					return;
				}

				// Disable this feature for GoodBids Main Site.
				if ( is_main_site() || is_super_admin() ) {
					return;
				}

				if ( ! $this->is_verified( get_current_blog_id() ) ) {
					wp_die( esc_html__( 'This site must be verified first.', 'goodbids' ) );
				}
			}
		);
	}

	/**
	 * Fires after a Nonprofit Finance and Contact Emails have been updated.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $meta_value The new meta value.
	 * @param mixed $prev_value The previous meta value.
	 * @param string $meta_key The meta key.
	 * @param int $site_id The site ID.
	 * @param array $field The field data.
	 */
	public function update_stripe_email( mixed $meta_value, mixed $prev_value, string $meta_key, int $site_id, array $field ): void {
		// Don't make any changes if the value hasn't changed.
		if ( $meta_value === $prev_value ) {
			return;
		}

		// Only run updates for primary and finance contact emails.
		if ( ! in_array( $field['field_id'], [ 'primary_contact_email', 'finance_contact_email' ] ) ) {
			return;
		}

		$nonprofit = new Nonprofit( $site_id );

		// Make sure we have a value.
		if ( ! $meta_value && 'finance_contact_email' === $field['field_id'] ) {
			$meta_value = $nonprofit->get_primary_contact_email();
		} elseif ( ! $meta_value && 'primary_contact_email' === $field['field_id'] ) {
			$meta_value = $nonprofit->get_finance_contact_email();
		}

		if ( ! $meta_value ) {
			$meta_value = $nonprofit->get_admin_email();
		}

		$nonprofit->update_stripe_email( $meta_value );
	}

	/**
	 * Display the Stripe Customer Details for Super Admins.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function stripe_data_field_callback() : void {
		if ( ! is_super_admin() ) {
			return;
		}

		$nonprofit       = new Nonprofit( $this->get_site_id() );
		$stripe_customer = $nonprofit->get_stripe_customer();

		goodbids()->load_view(
			'network/stripe-customer-data.php',
			compact( 'stripe_customer' )
		);
	}

	/**
	 * Allow Nonprofit Site settings to override default config.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function allow_nonprofit_config_override(): void {
		add_filter(
			'goodbids_config_var',
			function ( mixed $return, string $config_key ): mixed {
				if ( is_main_site() ) {
					return $return;
				}

				$override = $this->get_nonprofit_data(
					get_current_blog_id(),
					'config:' . $config_key,
					'read'
				);

				if ( ! $override ) {
					return $return;
				}

				return $override;
			},
			11,
			2
		);
	}
}
