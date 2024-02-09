<?php
/**
 * Auction Winner Confirmation: Send an email to the user that won an auction.
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
class AuctionWinnerConfirmation extends BaseEmail {

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
		$this->id             = 'goodbids_auction_winner_confirmation';
		$this->title          = __( 'Auction Winner Confirmation', 'goodbids' );
		$this->description    = __( 'Notification email is sent when a user has won an auction.', 'goodbids' );
		$this->template_html  = 'emails/auction-winner-confirmation.php';
		$this->template_plain = 'emails/plain/auction-winner-confirmation.php';

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
			/* translators: %s: site title */
			__( '[%s] Congratulations, you won!', 'goodbids' ),
			'{site_title}',
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
			/* translators: %s: reward title */
			__( 'You generosity earned a %s !', 'goodbids' ),
			'{auction.rewardTitle}'
		);
	}

	/**
	 * Get button text
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_button_text() {
		return __( 'Claim Your Reward', 'goodbids' );
	}

	/**
	 * Get Reward checkout flow url
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_reward_checkout_url() {
		// TODO set this up to pull the reward checkout url
		return '#';
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
				'instance'          => $this,
				'email_heading'     => $this->get_default_heading(),
				'button_text'       => $this->get_default_button_text(),
				'button_reward_url' => $this->get_reward_checkout_url(),
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
				'reward_url'    => $this->get_reward_checkout_url(),
			]
		);
	}
}
