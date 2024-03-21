<?php
/**
 * FreeBids Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users;

use GoodBids\Plugins\WooCommerce\Coupons;
use GoodBids\Utilities\Log;
use WP_User;

/**
 * Class for Free Bids
 *
 * @since 1.0.0
 */
class FreeBids {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const META_KEY = '_goodbids_free_bids';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const USE_FREE_BID_PARAM = 'use-free-bid';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STATUS_ALL = 'all';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STATUS_UNUSED = 'unused';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STATUS_USED = 'used';

	/**
	 * @since 1.0.0
	 */
	const NONCE_ACTION = 'admin_grant_free_bid';

	/**
	 * @since 1.0.0
	 */
	const REASON_FIELD = 'free_bid_reason';

	/**
	 * Initialize Free Bids
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Handle Free Bid via AJAX.
		$this->grant_free_bid_ajax();

		// Disable Free Bids on Main Site.
		if ( is_main_site() && ! is_network_admin() ) {
			return;
		}

		// Add UI for Super Admins to grant free bids to users.
		$this->free_bid_user_fields();

		// Set up JS Vars.
		$this->free_bid_js_vars();
	}

	/**
	 * Display the Grant Free Bid Admin UI
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function free_bid_user_fields(): void {
		$profile_fields = function ( WP_User $user ): void {
			if ( is_user_admin() || get_current_user_id() === $user->ID ) {
				return;
			}

			$user_id = $user->ID;

			goodbids()->load_view( 'admin/users/grant-free-bid.php', compact( 'user_id' ) );
		};

		add_action( 'show_user_profile', $profile_fields, 1 );
		add_action( 'edit_user_profile', $profile_fields, 1 );
	}

	/**
	 * Set up JS Vars
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function free_bid_js_vars(): void {
		add_action(
			'goodbids_enqueue_admin_scripts',
			function ( $handle ) {
				wp_localize_script(
					$handle,
					'goodbidsFreeBids',
					[
						'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
						'reasonFieldId'   => self::REASON_FIELD,
						'validationAlert' => [
							'title' => __( 'Validation Error', 'goodbids' ),
							'error' => __( 'Missing reason for granting free bid.', 'goodbids' ),
						],
						'errorAlert'      => [
							'title' => __( 'Error', 'goodbids' ),
						],
						'confirmedAlert'  => [
							'title' => __( 'Success!', 'goodbids' ),
							'text'  => __( 'The free bid was successfully granted.', 'goodbids' ),
						],
						'grantAction'     => 'goodbids_' . self::NONCE_ACTION,
						'nonceGrant'      => wp_create_nonce( self::NONCE_ACTION ),
					]
				);
			}
		);
	}

	/**
	 * Handle the AJAX action to grant the free bid.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function grant_free_bid_ajax(): void {
		add_action(
			'wp_ajax_goodbids_' . self::NONCE_ACTION,
			function () {
				Log::debug( 'Grant Free Bid AJAX Request' );
				list( $user_id, $reason ) = $this->ajax_request_validation();

				if ( $user_id === get_current_user_id() ) {
					wp_send_json_error(
						[
							'error' => __( 'You are not allowed to grant free bids to yourself.', 'goodbids' ),
						],
						200
					);
				}

				if ( ! $reason ) {
					wp_send_json_error(
						[
							'error' => __( 'Missing reason for granting free bid.', 'goodbids' ),
						],
						200
					);
				}

				if ( ! $this->award( $user_id, null, FreeBid::TYPE_ADMIN_GRANT, $reason, true ) ) {
					wp_send_json_error(
						[
							'error' => __( 'There was a problem granting the free bid.', 'goodbids' ),
						],
						200
					);
				}

				wp_send_json_success( [ 'done' ] );
			}
		);
	}

	/**
	 * Validate Free Bid Grant Nonce and return user_id and details
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function ajax_request_validation(): array {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );

		$data    = wp_unslash( $_POST );
		$user_id = intvaL( sanitize_text_field( $data['user_id'] ) );
		$reason  = sanitize_text_field( $data['reason'] );

		if ( ! $user_id || ! ( new WP_User( $user_id ) ) ) {
			wp_send_json_error(
				[
					'error' => __( 'Invalid data', 'goodbids' ),
				],
				200
			);
		}

		return [ $user_id, $reason ];
	}

	/**
	 * Get an array of all free bids for a User, filterable by status.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int    $user_id
	 * @param string  $status
	 * @param ?string $type
	 *
	 * @return FreeBid[]
	 */
	public function get( ?int $user_id = null, string $status = self::STATUS_ALL, ?string $type = null ): array {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		/** @var FreeBid[] $free_bids */
		$all_free_bids = get_user_meta( $user_id, self::META_KEY, true );

		if ( ! $all_free_bids || ! is_array( $all_free_bids ) ) {
			return [];
		}

		$collection = collect( $all_free_bids );

		if ( self::STATUS_ALL !== $status ) {
			$collection = $collection->filter(
				fn ( $free_bid ) => $status === $free_bid->get_status()
			);
		}

		if ( $type ) {
			$collection = $collection->filter(
				fn ( $free_bid ) => $type === $free_bid->get_type()
			);
		}

		return $collection
			->values()
			->all();
	}

	/**
	 * Get count of free bids
	 *
	 * @since 1.0.0
	 *
	 * @param ?int    $user_id
	 * @param string  $status
	 * @param ?string $type
	 *
	 * @return int
	 */
	public function get_count( ?int $user_id = null, string $status = self::STATUS_ALL, ?string $type = null ): int {
		return count( $this->get( $user_id, $status, $type ) );
	}

	/**
	 * Get total available free bids for a user.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return int
	 */
	public function get_available_count( ?int $user_id = null ): int {
		return $this->get_count( $user_id, self::STATUS_UNUSED );
	}

	/**
	 * Award a Free Bid to a User
	 *
	 * @since 1.0.0
	 *
	 * @param int    $user_id
	 * @param ?int   $auction_id
	 * @param string $type
	 * @param string $details
	 * @param bool   $notify_later
	 *
	 * @return bool
	 */
	public function award( int $user_id, ?int $auction_id = null, string $type = FreeBid::TYPE_PAID_BID, string $details = '', bool $notify_later = false ): bool {
		$free_bid = new FreeBid();

		if ( $auction_id ) {
			$free_bid->set_auction_id( $auction_id );
		}

		$free_bid->set_type( $type );
		$free_bid->set_details( $details );

		if ( $notify_later ) {
			$free_bid->awarded_notification = false;
		}

		$all_free_bids   = $this->get( $user_id );
		$all_free_bids[] = $free_bid;

		/**
		 * Called when a Free Bid is awarded to a User
		 *
		 * @since 1.0.0
		 *
		 * @param FreeBid $free_bid
		 * @param int     $user_id
		 */
		do_action( 'goodbids_award_free_bid', $free_bid, $user_id );

		return $this->save( $user_id, $all_free_bids );
	}

	/**
	 * Save Free Bids array to User Meta
	 *
	 * @since 1.0.0
	 *
	 * @param int       $user_id
	 * @param FreeBid[] $free_bids
	 *
	 * @return bool
	 */
	public function save( int $user_id, array $free_bids ): bool {
		$original = get_user_meta( $user_id, self::META_KEY, true );

		if ( $original === $free_bids ) {
			// Data is unchanged.
			return false;
		}

		return boolval( update_user_meta( $user_id, self::META_KEY, $free_bids ) );
	}

	/**
	 * Redeem a Free Bid
	 *
	 * @since 1.0.0
	 *
	 * @param int  $auction_id
	 * @param int  $order_id
	 * @param ?int $user_id
	 *
	 * @return bool
	 */
	public function redeem( int $auction_id, int $order_id, ?int $user_id = null ): bool {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		// Clear Cached Free Bid.
		$auction = goodbids()->auctions->get( $auction_id );
		delete_user_meta( $user_id, sprintf( Coupons::FREE_BID_COUPON_META_KEY, $auction->get_id(), $auction->get_variation_id() ) );

		// Locate the free bid to use.
		$all_free_bids    = $this->get( $user_id );
		$unused_free_bids = $this->get( $user_id, self::STATUS_UNUSED );

		if ( ! count( $unused_free_bids ) ) {
			Log::error( 'No available Free Bids to redeem', compact( 'user_id', 'auction_id', 'order_id' ) );
			return false;
		}

		// Use the first available free bid.
		foreach ( $all_free_bids as $free_bid ) {
			if ( self::STATUS_UNUSED !== $free_bid->get_status() ) {
				continue;
			}

			if ( $free_bid->redeem( $auction_id, $order_id ) ) {
				return $this->update( $user_id, $free_bid->get_id(), $free_bid );
			}

			Log::error( 'There was a problem redeeming the free bid.', compact( 'user_id', 'auction_id', 'order_id' ) );
			return false;
		}

		return true;
	}

	/**
	 * Update a free bid
	 *
	 * @since 1.0.0
	 *
	 * @param ?int    $user_id
	 * @param string  $free_bid_id
	 * @param FreeBid $free_bid
	 *
	 * @return bool
	 */
	public function update( ?int $user_id, string $free_bid_id, FreeBid $free_bid ): bool {
		$all_free_bids  = $this->get( $user_id );
		$free_bid_index = array_search( $free_bid_id, array_column( $all_free_bids, 'id' ) );

		if ( false === $free_bid_index ) {
			return false;
		}

		$updated = $all_free_bids[ $free_bid_index ];

		// Don't update if there are no changes.
		if ( $updated === $free_bid ) {
			return false;
		}

		$all_free_bids[ $free_bid_index ] = $free_bid;

		return $this->save( $user_id, $all_free_bids );
	}
}
