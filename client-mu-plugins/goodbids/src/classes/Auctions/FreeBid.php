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
		$this->earned_date       = current_time( 'Y-m-d H:i:s' );
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
}
