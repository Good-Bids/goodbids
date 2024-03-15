<?php
/**
 * Free Bid Object
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

/**
 * Class for Free Bid Objects
 *
 * @since 1.0.0
 */
class FreeBid {

	/**
	 * Paid Bid type of Free Bid.
	 *
	 * @since 1.0.0
	 */
	const TYPE_PAID_BID = 'paid_bid';

	/**
	 * Referral type of Free Bid.
	 *
	 * @since 1.0.0
	 */
	const TYPE_REFERRAL = 'referral';

	/**
	 * Admin Granted Free Bid type.
	 *
	 * @since 1.0.0
	 */
	const TYPE_ADMIN_GRANT = 'admin_grant';

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
	public string $status = Bids::FREE_BID_STATUS_UNUSED;

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
	 * Has the user been notified of this yet?
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public bool $awarded_notification = true;

	/**
	 * Type of Free Bid
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	public ?string $type = null;

	/**
	 * Description of the Free Earned Bid
	 *
	 * @since 1.0.0
	 * @deprecated 1.0.0 Use `details` instead.
	 * @var ?string
	 */
	public ?string $description = null;

	/**
	 * Details of the Free Earned Bid
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	public ?string $details = null;

	/**
	 * ID of the Site Free Bid was Used
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	public ?int $site_id_used = null;

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
	 * Order ID when Free Bid was redeemed.
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	public ?int $order_id_redeemed = null;

	/**
	 * Value of Bid.
	 *
	 * @since 1.0.0
	 * @var ?float
	 */
	public ?float $bid_value = null;

	/**
	 * Initialize Free Bid Object
	 *
	 * @param ?int $auction_id_earned
	 *
	 * @since 1.0.0
	 */
	public function __construct( ?int $auction_id_earned = null ) {
		if ( $auction_id_earned ) {
			$this->set_auction_id( $auction_id_earned );
		}

		if ( ! $this->id ) {
			$this->id = uniqid( 'GBFB-' );
		}

		if ( ! $this->earned_date ) {
			$this->earned_date = current_time( 'Y-m-d H:i:s' );
		}

		return $this;
	}

	/**
	 * Set the Auction ID
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return FreeBid
	 */
	public function set_auction_id( int $auction_id ): FreeBid {
		$this->auction_id_earned = $auction_id;
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
	 * Sets the Free Earned Type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type
	 *
	 * @return FreeBid
	 */
	public function set_type( string $type ): FreeBid {
		$this->type = $type;
		return $this;
	}

	/**
	 * Returns the type of the Free Bid
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_type(): string {
		if ( ! $this->type ) {
			return self::TYPE_PAID_BID;
		}
		return $this->type;
	}

	/**
	 * Returns the type of the Free Bid for display
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_type_display(): string {
		return ucwords( str_replace( '_', ' ', $this->get_type() ) );
	}

	/**
	 * Displays the type of the Free Bid
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function display_type(): void {
		echo esc_html( $this->get_type_display() );
	}

	/**
	 * Get the type action performed to be awarded free bid.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_type_action(): string {
		if ( $this->get_type() === self::TYPE_REFERRAL ) {
			return __( 'Referral', 'goodbids' );
		}

		if ( $this->get_type() === self::TYPE_ADMIN_GRANT ) {
			return __( 'admin grant', 'goodbids' );
		}

		// Default to Bid.
		return __( 'Bid', 'goodbids' );
	}

	/**
	 * Sets the Details for the Free Earned Bid.
	 *
	 * @since 1.0.0
	 *
	 * @param string $details
	 *
	 * @return FreeBid
	 */
	public function set_details( string $details ): FreeBid {
		$this->details = $details;
		return $this;
	}

	/**
	 * Get the Free Bid Details
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_details(): string {
		if ( ! empty( $this->details ) ) {
			return $this->details;
		}

		if ( ! empty( $this->description ) ) {
			return $this->description;
		}

		return '';
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
	 * @param ?string $title
	 *
	 * @return void
	 */
	public function display_auction_link( ?string $title = '' ): void {
		if ( ! $this->auction_id_used || ! $this->site_id_used ) {
			esc_html_e( 'N/A', 'goodbids' );
			return;
		}

		goodbids()->sites->swap(
			function () use ( $title ) {
				printf(
					'<a href="%s" title="%s" target="_blank" rel="nofollow noopener">%s (ID: %s)</a>',
					esc_url( get_permalink( $this->auction_id_used ) ),
					$title ? esc_attr( $title ) : '',
					esc_html( get_the_title( $this->auction_id_used ) ),
					esc_html( $this->auction_id_used )
				);
			},
			$this->site_id_used
		);
	}

	/**
	 * Redeem this Free Bid
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $order_id
	 *
	 * @return bool
	 */
	public function redeem( int $auction_id, int $order_id ): bool {
		$order = wc_get_order( $order_id );

		// Verify order exists.
		if ( ! $order ) {
			return false;
		}

		if ( $this->get_status() !== Bids::FREE_BID_STATUS_UNUSED ) {
			return false;
		}

		$this->used_date         = current_time( 'Y-m-d H:i:s' );
		$this->status            = Bids::FREE_BID_STATUS_USED;
		$this->auction_id_used   = $auction_id;
		$this->site_id_used      = get_current_blog_id();
		$this->order_id_redeemed = $order->get_id();
		$this->bid_value         = $order->get_subtotal();

		return true;
	}

	/**
	 * Used to notify users of awarded free bids later then when they were awarded.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function did_awarded_notification(): bool {
		return false !== $this->awarded_notification;
	}

	/**
	 * Mark the Free Bid as Notified.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function mark_as_notified(): void {
		$this->awarded_notification = true;
	}
}
