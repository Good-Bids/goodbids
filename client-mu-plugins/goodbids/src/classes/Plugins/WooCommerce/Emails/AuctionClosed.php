<?php
/**
 * Auction Closed: Email the users when an auction closes.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Ending Email
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionClosed extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_closed';
		$this->title          = __( 'Auction Closed', 'goodbids' );
		$this->description    = __( 'Notification email is sent when an auction goes closes.', 'goodbids' );
		$this->template_html  = 'emails/auction-closed.php';
		$this->template_plain = 'emails/plain/auction-closed.php';
		$this->watcher_email  = true;
		$this->bidder_email   = true;
	}

	/**
	 * Add a custom footer.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_customizations(): void {
		add_action( 'woocommerce_email_footer', [ $this, 'all_auctions_html' ], 7, 2 );
	}

	/**
	 * Get email subject.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_subject(): string {
		return sprintf(
			/* translators: %1$s: site title, %2$s: auction title */
			__( '[%1$s] %2$s has ended', 'goodbids' ),
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
	public function get_default_heading(): string {
		return sprintf(
			/* translators: %1$s: Site Title, %2$s: Total Raised by Auction */
			__( 'You helped %1$s raise %2$s!', 'goodbids' ),
			'{site_title}',
			'{auction.total_raised}'
		);
	}

	/**
	 * Get button text
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_button_text(): string {
		return __( 'See Auction Results', 'goodbids' );
	}

	/**
	 * Set Button URL
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_button_url(): string {
		return '{auction.url}';
	}

	/**
	 * Display Link to All Auctions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function all_auctions_html(): void {
		printf(
			'<p style="text-align:center;">%s <a href="%s">%s</a>',
		esc_html__( 'Want to support another great GoodBids?', 'goodbids' ),
			'{auctions_url}',
			esc_html__( 'View All Auctions', 'goodbids' )
		);
	}
}
