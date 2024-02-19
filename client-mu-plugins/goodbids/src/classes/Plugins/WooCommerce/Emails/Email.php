<?php
/**
 * Primary class used for extending custom WC Emails for GoodBids.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

use WC_Email;
use WP_User;

/**
 * Custom GoodBids Email class.
 *
 * @since 1.0.0
 * @extends WC_Email
 */
class Email extends WC_Email {

	/**
	 * User ID.
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	public ?int $user_id = null;

	/**
	 * Email vars.
	 *
	 * @since 1.0.0
	 * @var string[]
	 */
	public array $email_vars = [];

	/**
	 * Set default vars and placeholders
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->init();
	}

	/**
	 * Initialize the custom email.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init(): void {
		// Set up empty placeholders.
		$this->init_placeholders();

		// Set up custom fields.
		$this->init_fields();

		if ( $this->get_default_button_text() ) {
			$this->add_button_support();
		}
	}

	/**
	 * Add a custom field for button text.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_button_support(): void {
		/* translators: %s: list of placeholders */
		$placeholder_text  = sprintf( __( 'Available placeholders: %s', 'goodbids' ), '<code>' . esc_html( implode( '</code>, <code>', array_keys( $this->placeholders ) ) ) . '</code>' );

		// Custom Fields
		$this->set_form_field(
			'button_text',
			[
				'title'       => __( 'Button Text', 'woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $placeholder_text,
				'placeholder' => $this->get_default_button_text(),
				'default'     => '',
			]
		);
	}

	/**
	 * Initialize custom email vars.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_vars(): void {}

	/**
	 * Initialize custom email placeholders.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_placeholders(): void {}

	/**
	 * Initialize custom email fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_fields(): void {}


	/**
	 * Get the user's name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_user_name(): string {
		if ( ! $this->user_id ) {
			return '';
		}

		$user       = new WP_User( $this->user_id );
		$first_name = get_user_meta( $this->user_id, 'first_name', true );

		return $first_name ?: $user->user_login;
	}

	/**
	 * Custom Button Text Support. Override this method to add a custom button.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_default_button_text(): string {
		return '';
	}

	/**
	 * Get the button text.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_button_text(): string {
		/**
		 * Filter the button text.
		 * @since 1.0.0
		 *
		 * @param string $button_text The button text.
		 * @param object $object The object.
		 * @param object $this The email object.
		 */
		return apply_filters(
			'goodbids_button_text_' . $this->id,
			$this->format_string(
				$this->get_option( 'button_text', $this->get_default_button_text() )
			),
			$this->object,
			$this
		);
	}

	/**
	 * Get the button url.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_button_url(): string {
		return '#';
	}

	/**
	 * Determine if the email should actually be sent and setup email merge variables
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $object
	 * @param ?int  $user_id
	 *
	 * @return void
	 */
	public function trigger( mixed $object = null, ?int $user_id = null ): void {
		$this->setup_locale();

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		if ( $object ) {
			$this->object = $object;
		}

		if ( $user_id ) {
			$this->user_id = $user_id;
		}

		// Default Email Vars.
		$this->add_email_var( 'email_heading', $this->get_heading() );
		$this->add_email_var( 'additional_content', $this->get_additional_content() );
		$this->add_email_var( 'button_text', $this->get_button_text() );
		$this->add_email_var( 'button_url', $this->get_button_url() );

		// Default placeholders.
		$this->add_placeholder( 'user.name', $this->get_user_name() );

		// Allow Email Classes to customize the vars.
		$this->init_vars();

		// Fill Placeholders with Values.
		$this->init_placeholders();

		// Woohoo, send the email!
		$this->send(
			$this->get_recipient(),
			$this->get_subject(),
			$this->get_content(),
			$this->get_headers(),
			$this->get_attachments()
		);

		$this->restore_locale();
	}

	/**
	 * Get the email recipient
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_recipient(): string {
		$recipient = parent::get_recipient();

		if ( ! $recipient ) {
			// this sets the recipient to the settings defined below in init_form_fields()
			$recipient = $this->get_option( 'recipient' );
		}

		// if none was entered, just use the WP admin email as a fallback
		if ( ! $recipient ) {
			$recipient = get_option( 'admin_email' );
		}

		return $recipient;
	}

	/**
	 * Return the vars used in this email template.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_email_vars(): array {
		return $this->email_vars;
	}

	/**
	 * Add an email var for the templates.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return void
	 */
	protected function add_email_var( string $key, mixed $value ): void {
		$this->email_vars[ $key ] = $value;
	}

	/**
	 * Add an email var for the templates.
	 *
	 * @since 1.0.0
	 *
	 * @param string $token
	 * @param mixed  $value
	 *
	 * @return void
	 */
	protected function add_placeholder( string $token, mixed $value ): void {
		$this->placeholders[ $token ] = $value;
	}

	/**
	 * Get the HTML Email Content.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_content_html(): string {
		return wc_get_template_html(
			$this->template_html,
			$this->get_email_vars()
		);
	}

	/**
	 * Get the plain text email content.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_content_plain(): string {
		return wc_get_template_html(
			$this->template_plain,
			$this->get_email_vars()
		);
	}

	/**
	 * Add or change a form field.
	 * @since 1.0.0
	 *
	 * @param string $key
	 * @param array  $field
	 *
	 * @return void
	 */
	public function set_form_field( string $key, array $field ): void {
		// Update Existing.
		if ( array_key_exists( $key, $this->form_fields ) ) {
			$this->form_fields[ $key ] = $field;
			return;
		}

		// Add New before Additional Content.
		$form_fields = [];
		foreach ( $this->form_fields as $form_field_key => $form_field ) {
			if ( 'additional_content' === $form_field_key ) {
				$form_fields[ $key ] = $field;
			}

			$form_fields[ $form_field_key ] = $form_field;
		}

		$this->form_fields = $form_fields;
	}
}
