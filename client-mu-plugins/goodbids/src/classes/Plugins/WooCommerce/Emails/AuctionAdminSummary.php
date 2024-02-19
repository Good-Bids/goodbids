<?php
/**
 * Auction Watchers Live: Send an email to the users that are watching when an auction goes live.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

use GoodBids\Plugins\WooCommerce\Emails\BaseEmail;

/**
 * Auction Watchers Live extend the custom BaseEmail class
 *
 * @since 1.0.0
 * @extends BaseEmail
 */
class AuctionAdminSummary extends BaseEmail {

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
		$this->id             = 'goodbids_auction_admin_summary';
		$this->title          = __( 'Auction Admin Summary', 'goodbids' );
		$this->description    = __( 'Notification email sent to admins when an auction closes.', 'goodbids' );
		$this->template_html  = 'emails/auction-admin-summery.php';
		$this->template_plain = 'emails/plain/auction-admin-summery.php';

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
			/* translators: %1$s: site title, %2$s: auction title */
			__( '[%1$s] Auction summary for %2$s', 'goodbids' ),
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
			/* translators: %1$s: auction total raised, %2$s: site title */
			__( '%1$s raised for %2$s!', 'goodbids' ),
			'{auction.totalRaised}',
			'{site_title}'
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
			]
		);
	}
}
