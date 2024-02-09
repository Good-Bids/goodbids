<?php
/**
 * Auction Outbid: Send an email to the user that was out bid on an auction.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

use GoodBids\Plugins\WooCommerce\Emails\BaseEmail;
/**
 * Auction Outbid extend the custom BaseEmail class
 *
 * @since 1.0.0
 * @extends BaseEmail
 */
class AuctionOutbid extends BaseEmail {

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
		$this->id             = 'goodbids_auction_outbid';
		$this->title          = __( 'Auction Outbid', 'goodbids' );
		$this->description    = __( 'Notification email is sent when a user is out bid on an auction.', 'goodbids' );
		$this->template_html  = 'emails/auction-outbid.php';
		$this->template_plain = 'emails/plain/auction-outbid.php';

		// TODO: Trigger this email.

		// Call parent constructor to load any other defaults not explicitly defined here
		parent::__construct();
	}

	/**
	 * Get email heading.
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'It’s not too late!', 'goodbids' );
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
	 * Get email subject.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_subject() {
		return sprintf(
			/* translators: %s: site title */
			__( '[%s] You’ve been outbid', 'goodbids' ),
			'{site_title}',
		);
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

		$this->default_trigger( $user_id );

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
				'instance'      => $this,
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
				'instance'      => $this,
				'email_heading' => $this->get_default_heading(),
				'button_text'   => $this->get_default_button_text(),
			]
		);
	}
}
