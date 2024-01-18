<?php
/**
 * Email Notification: Auction Live for watchers
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\EmailNotifications;

defined( 'ABSPATH' ) || exit;

use WC_Email;


/**
 * Auction live for watchers custom WooCommerce email class
 *
 * @since 1.0.0
 * @extends \WC_Email
 */
class AuctionLiveWatchers extends WC_Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->id             = 'wc_auction_live_watchers';
		$this->title          = __( 'Auction Live Watchers', 'goodbids' );
		$this->description    = __( 'Auction Live Watchers Notification emails is sent when an auction is live.', 'goodbids' );
		$this->heading        = __( 'Auction Live Watchers', 'goodbids' );
		$this->subject        = __( 'Auction Live Watchers', 'goodbids' );
		$this->template_html  = 'emails/admin-new-order.php';
		$this->template_plain = 'emails/plain/admin-new-order.php';

		// Trigger on 'wc_auction_live_watchers'
		add_action( 'woocommerce_order_status_pending_to_processing_notification', [ $this, 'trigger' ] );
		add_action( 'woocommerce_order_status_failed_to_processing_notification', [ $this, 'trigger' ] );

		// Call parent constructor to load any other defaults not explicity defined here
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
	 * @param int $order_id
	 * @return void
	 */
	public function trigger( $order_id ): void {

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
				'title'   => 'Enable/Disable',
				'type'    => 'checkbox',
				'label'   => 'Enable this email notification',
				'default' => 'yes',
			],
			'recipient'          => [
				'title'       => 'Recipient(s)',
				'type'        => 'text',
				'description' => sprintf( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', esc_attr( get_option( 'admin_email' ) ) ),
				'placeholder' => '',
				'default'     => '',
			],
			'subject'            => [
				'title'       => 'Subject',
				'type'        => 'text',
				'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
				'placeholder' => '',
				'default'     => '',
			],
			'heading'            => [
				'title'       => 'Email Heading',
				'type'        => 'text',
				'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
				'placeholder' => '',
				'default'     => '',
			],
			'additional_content' => [
				'title'       => 'Additional content',
				'type'        => 'textarea',
				'description' => sprintf( __( 'This controls the additional content contained within the email notification.' ) ),
				'placeholder' => '',
				'default'     => '',
			],
			'email_type'         => [
				'title'       => 'Email type',
				'type'        => 'select',
				'description' => 'Choose which format of email to send.',
				'default'     => 'html',
				'class'       => 'email_type',
				'options'     => [
					'plain'     => 'Plain text',
					'html'      => 'HTML',
					'woocommerce',
					'multipart' => 'Multipart',
				],
			],
		];
	}
}
