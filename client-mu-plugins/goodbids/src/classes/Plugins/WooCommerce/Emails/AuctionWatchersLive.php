<?php
/**
 * Auction Watchers Live: Send an email to the users that are watching when an auction goes live.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

use WC_Email;
use WP_User;

/**
 * Auction Watchers Live extend the custom WooCommerce email class
 *
 * @since 1.0.0
 * @extends WC_Email
 */
class AuctionWatchersLive extends WC_Email {

	/**
	 * Site Name
	 *
	 * @var string
	 */
	public $site_name;

	/**
	 * User login name.
	 *
	 * @var string
	 */
	public $user_name;

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
		$this->id             = 'goodbids_auction_watchers_live';
		$this->title          = __( 'Auction Watchers Live', 'goodbids' );
		$this->description    = __( 'Auction Watchers Live Notification emails is sent when an auction goes live.', 'goodbids' );
		$this->template_html  = 'emails/auction-watchers-live.php';
		$this->template_plain = 'emails/plain/auction-watchers-live.php';

		// TODO: Trigger this email.

		// Call parent constructor to load any other defaults not explicitly defined here
		parent::__construct();
	}

	/**
	 * Get site name.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_site_name() {
		return get_bloginfo( 'name' );
	}


	/**
	 * Get email subject.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_subject() {
		return sprintf(
			'%s %s is live',
			esc_html( $this->get_site_name() ),
			esc_html( $this->get_auction_title() )
		);
	}

	/**
	 * Get email heading.
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'Ready to GOODBID?', 'goodbids' );
	}

	/**
	 * Get button text
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_button_text() {
		return __( 'Bid Now', 'goodbids' );
	}

	/**
	 * Get the auction title
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_title() {
		return __( '{auction_title}', 'goodbids' );
	}

	/**
	 * Get the auction goal
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_goal() {
		return __( '{auction_goal}', 'goodbids' );
	}

	/**
	 * Get the auction url
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_url() {
		return __( '{auction_url}', 'goodbids' );
	}


	/**
	 * Get the auction starting bid
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_starting_bid() {
		return __( '{auction_starting_bid}', 'goodbids' );
	}

	/**
	 * Get user name.
	 *
	 * @since   1.0.0
	 * @param mixed $id
	 * @return string
	 */
	public function get_user_name() {
		if ( ! $this->user_id ) {
			return;
		}

		$user_first_name = get_user_meta( $this->user_id, 'first_name', true );

		return $user_first_name ? $user_first_name : $this->object->user_login;
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
			$this->object    = new WP_User( $user_id );
			$this->user_id   = $this->object->ID;
			$this->user_name = stripslashes( $this->get_user_name() );
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
				'email_heading'        => $this->get_default_heading(),
				'user_name'            => $this->get_user_name(),
				'site_name'            => $this->get_site_name(),
				'auction_title'        => $this->get_auction_title(),
				'auction_goal'         => $this->get_auction_goal(),
				'auction_url'          => $this->get_auction_url(),
				'auction_starting_bid' => $this->get_auction_starting_bid(),
				'button_text'          => $this->get_default_button_text(),
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
				'email_heading'        => $this->get_default_heading(),
				'user_name'            => $this->get_user_name(),
				'site_name'            => $this->get_site_name(),
				'auction_title'        => $this->get_auction_title(),
				'auction_goal'         => $this->get_auction_goal(),
				'auction_url'          => $this->get_auction_url(),
				'auction_starting_bid' => $this->get_auction_starting_bid(),
				'button_text'          => $this->get_default_button_text(),
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
