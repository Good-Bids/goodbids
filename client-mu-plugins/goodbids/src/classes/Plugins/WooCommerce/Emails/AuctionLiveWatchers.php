<?php
/**
 * Email Notification: Auction Live for watchers
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

use WC_Email;

/**
 * Auction live for watchers custom WooCommerce email class
 *
 * @since 1.0.0
 * @extends WC_Email
 */
class AuctionLiveWatchers extends WC_Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->id             = 'goodbids_auction_live_watchers';
		$this->title          = __( 'Auction Live Watchers', 'goodbids' );
		$this->description    = __( 'Auction Live Watchers Notification emails is sent when an auction goes live.', 'goodbids' );
		$this->heading        = __( 'Auction Live Watchers', 'goodbids' );
		$this->subject        = __( 'Auction Live Watchers', 'goodbids' );
		$this->template_html  = 'emails/admin-new-order.php';
		$this->template_plain = 'emails/plain/admin-new-order.php';

		// Call parent constructor to load any other defaults not explicitly defined here
		parent::__construct();

		// this sets the recipient to the settings defined below in init_form_fields()
		$this->recipient = $this->get_option( 'recipient' );

		// if none was entered, just use the WP admin email as a fallback
		if ( ! $this->recipient ) {
			$this->recipient = get_option( 'admin_email' );
		}
	}

	/**
	 * Determine if the email should actually be sent and setup email merge variables
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function trigger(): void {

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		// woohoo, send the email!
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * get_content_html function.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_content_html(): string {
		ob_start();
		woocommerce_get_template(
			$this->template_html,
			[
				'order'         => $this->object,
				'email_heading' => $this->get_heading(),
			]
		);
		return ob_get_clean();
	}


	/**
	 * get_content_plain function.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_content_plain(): string {
		ob_start();
		woocommerce_get_template(
			$this->template_plain,
			[
				'order'         => $this->object,
				'email_heading' => $this->get_heading(),
			]
		);
		return ob_get_clean();
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
			'recipient'          => [
				'title'       => __( 'Recipient(s)', 'goodbids' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => sprintf( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', esc_attr( get_option( 'admin_email' ) ) ),
				'placeholder' => '',
				'default'     => '',
			],
			'subject'            => [
				'title'       => __( 'Subject', 'goodbids' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
				'placeholder' => '',
				'default'     => '',
			],
			'heading'            => [
				'title'       => __( 'Email Heading', 'goodbids' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
				'placeholder' => '',
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
				'description' => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'     => 'html',
				'class'       => 'email_type wc-enhanced-select',
				'options'     => $this->get_email_type_options(),
				'desc_tip'    => true,
			],
		];
	}
}
