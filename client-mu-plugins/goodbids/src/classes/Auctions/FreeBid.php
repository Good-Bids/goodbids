<?php
/**
 * Free Bid Object
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use GoodBids\Users\Users;

/**
 * Class for Free Bid Objects
 *
 * @since 1.0.0
 */
class FreeBid {

	/**
	 * Unique Identifier.
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	public ?string $id = null;

	/**
	 * @since 1.0.0
	 * @var string
	 */
	public string $status = Users::FREE_BID_STATUS_UNUSED;

	/**
	 * ID of the Auction Free Bid was Earned
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	public ?int $auction_id_earned = null;

	/**
	 * Date/Time the Free Bid was Earned
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	public ?string $earned_date = null;

	/**
	 * Description of the Free Earned Bid
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	public ?string $description = null;

	/**
	 * ID of the Auction Free Bid was Used
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	public ?int $auction_id_used = null;

	/**
	 * Date/Time the Free Bid was Used
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	public ?string $used_date = null;

	/**
	 * Initialize Free Bid Object
	 *
	 * @since 1.0.0
	 */
	public function __construct( int $auction_id_earned ) {
		$this->auction_id_earned = $auction_id_earned;

		if ( ! $this->id ) {
			$this->id = uniqid( 'GBFB-' );
		}

		if ( ! $this->earned_date ) {
			$this->earned_date = current_time( 'Y-m-d H:i:s' );
		}

		return $this;
	}

	/**
	 * Sets the status of the Free Bid.
	 *
	 * @since 1.0.0
	 *
	 * @param string $status
	 *
	 * @return FreeBid
	 */
	public function set_status( string $status ): FreeBid {
		$this->status = $status;
		return $this;
	}

	/**
	 * Returns the status of the Free Bid
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_status(): string {
		return $this->status;
	}

	/**
	 * Displays the status of the Free Bid
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function display_status(): void {
		echo esc_html( ucwords( $this->get_status() ) );
	}

	/**
	 * Sets the Description for the Free Earned Bid.
	 *
	 * @since 1.0.0
	 *
	 * @param string $description
	 *
	 * @return FreeBid
	 */
	public function set_description( string $description ): FreeBid {
		$this->description = $description;
		return $this;
	}

	/**
	 * Display formatted Earned Date
	 *
	 * @since 1.0.0
	 *
	 * @param string $format
	 *
	 * @return void
	 */
	public function display_earned_date( string $format = 'n/j/y g:i a' ): void {
		echo wp_kses_post( goodbids()->utilities->format_date_time( $this->earned_date, $format ) );
	}

	/**
	 * Display formatted Earned Date
	 *
	 * @since 1.0.0
	 *
	 * @param string $format
	 *
	 * @return void
	 */
	public function display_used_date( string $format = 'n/j/y g:i a' ): void {
		if ( ! $this->used_date ) {
			esc_html_e( 'N/A', 'goodbids' );
			return;
		}

		echo wp_kses_post( goodbids()->utilities->format_date_time( $this->used_date, $format ) );
	}

	/**
	 * Display a link to an Auction by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 * @param ?string $title
	 *
	 * @return void
	 */
	public function display_auction_link( ?int $auction_id, ?string $title = '' ): void {
		if ( ! $auction_id ) {
			esc_html_e( 'N/A', 'goodbids' );
			return;
		}

		printf(
			'<a href="%s" title="%s" target="_blank" rel="nofollow noopener">%s (ID: %s)</a>',
			esc_url( get_permalink( $auction_id ) ),
			$title ? esc_attr( $title ) : '',
			esc_html( get_the_title( $auction_id ) ),
			esc_html( $auction_id )
		);
	}
}
