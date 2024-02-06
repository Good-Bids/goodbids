<?php
/**
 * Auction Closed: Send an email to the users when an auction closes.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

use WC_Email;
use WP_User;

/**
 * Auction Watchers Live extend WC_Email
 *
 * @since 1.0.0
 * @extends WC_Email
 */
class AuctionClosed extends WC_Email {

	/**
	 * User ID.
	 *
	 * @var integer
	 */
	public $user_id;

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->id             = 'goodbids_auction_closed';
		$this->title          = __( 'Auction Closed', 'goodbids' );
		$this->description    = __( 'Notification email is sent when an auction goes closes.', 'goodbids' );
		$this->template_html  = 'emails/auction-closed.php';
		$this->template_plain = 'emails/plain/auction-closed.php';

		// TODO: Trigger this email.

		// Call parent constructor to load any other defaults not explicitly defined here
		parent::__construct();
	}

	/**
	 * Get email subject.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_subject() {
		return sprintf(
			'%s %s has ended',
			'{site_title}',
			'{auction.title}'
		);
	}

	/**
	 * Get email heading.
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_heading() {
		return sprintf(
			__( 'You helped %1$s raise %2$s!', 'goodbids' ),
			'{site_title}',
			'{auction.totalRaised}'
		);
	}

	/**
	 * Get button text
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_button_text() {
		return __( 'See Auction Results', 'goodbids' );
	}

	/**
	 * Get the email recipients
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
	 * Determine if the email should actually be sent and setup email merge variables
	 *
	 * @since 1.0.0
	 * @param mixed $user_id
	 * @return void
	 */
	public function trigger( $user_id ): void {
		$this->setup_locale();

		// TODO set up check before sending email
		if ( $user_id ) {
			$this->object  = new WP_User( $user_id );
			$this->user_id = $this->object->ID;
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		// woohoo, send the email!
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

		$this->restore_locale();
	}

	/**
	 * get_content_html function.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_content_html(): string {
		return wc_get_template_html(
			$this->template_html,
			[
				'email_heading' => $this->get_default_heading(),
				'button_text'   => $this->get_default_button_text(),
			]
		);
	}


	/**
	 * get_content_plain function.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_content_plain(): string {
		return wc_get_template_html(
			$this->template_plain,
			[
				'email_heading' => $this->get_default_heading(),
				'button_text'   => $this->get_default_button_text(),
			]
		);
	}

	/**
	 * Initialize Settings Form Fields
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init_form_fields(): void {

		$this->form_fields = [
			'enabled'            => [
				'title'   => __( 'Enable/Disable', 'goodbids' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable this email notification', 'goodbids' ),
				'default' => 'yes',
			],
			'subject'            => [
				'title'       => __( 'Subject', 'goodbids' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->get_subject() ),
				'placeholder' => $this->get_default_subject(),
				'default'     => '',
			],
			'heading'            => [
				'title'       => __( 'Email Heading', 'goodbids' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->get_heading() ),
				'placeholder' => $this->get_default_heading(),
				'default'     => '',
			],
			'additional_content' => [
				'title'       => __( 'Additional content', 'goodbids' ),
				'description' => __( 'Text to appear below the main email content.', 'goodbids' ),
				'css'         => 'width:400px; height: 75px;',
				'placeholder' => __( 'N/A', 'goodbids' ),
				'type'        => 'textarea',
				'default'     => $this->get_default_additional_content(),
				'desc_tip'    => true,
			],
			'email_type'         => [
				'title'       => __( 'Email type', 'goodbids' ),
				'type'        => 'select',
				'description' => __( 'Choose which format of email to send.', 'goodbids' ),
				'default'     => 'html',
				'class'       => 'email_type wc-enhanced-select',
				'options'     => $this->get_email_type_options(),
				'desc_tip'    => true,
			],
		];
	}
}
