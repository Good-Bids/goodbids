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
 * Auction Admin Live extends the custom AuctionWatchersLive class
 *
 * @since 1.0.0
 * @extends AuctionWatchersLive
 */
class AuctionAdminLive extends AuctionWatchersLive {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_admin_live';
		$this->title          = __( 'Auction Admin Live', 'goodbids' );
		$this->description    = __( 'This Email is sent to the site admin when an Auction goes live.', 'goodbids' );
		$this->template_html  = 'emails/auction-admin-live.php';
		$this->template_plain = 'emails/plain/auction-admin-live.php';
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
		return '{auction.url}';
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
		$this->add_email_var( 'auction_goal', $auction?->get_goal() );
		$this->add_email_var( 'auction_expected_high_bid', $auction?->get_expected_high_bid() );
	}
}
