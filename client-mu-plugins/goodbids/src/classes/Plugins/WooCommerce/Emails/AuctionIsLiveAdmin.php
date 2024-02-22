<?php
/**
 * Auction Admin Live: Email the site Admin when an Auction goes live.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

use GoodBids\Auctions\Auction;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Starting Email (Admin)
 *
 * @since 1.0.0
 * @extends AuctionIsLive
 */
class AuctionIsLiveAdmin extends AuctionIsLive {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_live_admin';
		$this->title          = __( 'Auction is Live (Admin)', 'goodbids' );
		$this->description    = __( 'Notification email sent to all site admins when an Auction goes live.', 'goodbids' );
		$this->template_html  = 'emails/auction-is-live-admin.php';
		$this->template_plain = 'emails/plain/auction-is-live-admin.php';
		$this->watcher_email  = false; // Override parent class value.
		$this->admin_email    = true;

		// Parent class triggers this email on auction start.
	}

	/**
	 * Add a custom footer.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_customizations(): void {
		add_action( 'woocommerce_email_footer', [ $this, 'login_link' ], 7, 2 );
	}

	/**
	 * Remove custom footer.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function remove_customizations(): void {
		remove_action( 'woocommerce_email_footer', [ $this, 'login_link' ], 7 );
	}

	/**
	 * Get email heading.
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_heading(): string {
		return __( 'Let the GOODBIDs begin!', 'goodbids' );
	}

	/**
	 * Get button text
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_button_text(): string {
		return __( 'Track this Auction', 'goodbids' );
	}

	/**
	 * Set Button URL
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_button_url(): string {
		return '{auction.admin_url}';
	}

	/**
	 * Customize the plain text footer
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function plain_text_footer(): void {

		echo "\n\n----------------------------------------\n\n";

		if ( $this->get_button_text() ) {
			printf(
				"%s:\n%s",
				esc_html( wp_strip_all_tags( wptexturize( $this->get_button_text() ) ) ),
				esc_html( wp_strip_all_tags( wptexturize( $this->get_button_url() ) ) ),
			);
			echo "\n\n----------------------------------------\n\n";
		}

		printf(
		/* translators: %1$s: Login URL */
			'Login to your site to view additional auction information: %1$s',
			'{login_url}'
		);

		echo "\n\n----------------------------------------\n\n";

		/**
		 * Show user-defined additional content - this is set in each email's settings.
		 */
		if ( $this->get_additional_content() ) {

			echo esc_html( wp_strip_all_tags( wptexturize( $this->get_additional_content() ) ) );
			echo "\n\n----------------------------------------\n\n";
		}
	}

	/**
	 * Add custom vars
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_vars(): void {
		$auction = $this->object instanceof Auction ? $this->object : null;
		$this->add_email_var( 'auction_estimated_value', $auction?->get_estimated_value() );
		$this->add_email_var( 'auction_estimated_value_formatted', $auction?->get_estimated_value_formatted() );
		$this->add_email_var( 'auction_goal', $auction?->get_goal() );
		$this->add_email_var( 'auction_goal_formatted', $auction?->get_goal_formatted() );
		$this->add_email_var( 'auction_expected_high_bid', $auction?->get_expected_high_bid() );
		$this->add_email_var( 'auction_expected_high_bid_formatted', $auction?->get_expected_high_bid_formatted() );
	}

	/**
	 * Display a login link
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function login_link(): void {
		printf(
			'<p><a href="%s">%s</a> %s</p>',
			'{login_url}',
			esc_html__( 'Login to your site', 'goodbids' ),
			esc_html__( 'to view additional auction information.', 'goodbids' )
		);
	}
}
