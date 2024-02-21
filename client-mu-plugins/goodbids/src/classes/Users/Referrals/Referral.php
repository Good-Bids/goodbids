<?php
/**
 * Referral Instance
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users\Referrals;

use GoodBids\Users\Referrals;
use GoodBids\Utilities\Log;
use WP_Post;
use WP_User;

/**
 * Instance of Referral
 * @since 1.0.0
 */
class Referral {

	/**
	 * @since 1.0.0
	 */
	const AUCTION_ID_META_KEY = '_auction_id';

	/**
	 * @since 1.0.0
	 */
	const ORDER_ID_META_KEY = '_order_id';

	/**
	 * The Referral Post ID.
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $referral_id = null;

	/**
	 * Instance of the Referral Post.
	 *
	 * @since 1.0.0
	 * @var ?WP_Post
	 */
	private ?WP_Post $post = null;

	/**
	 * The ID of the user being referred. (AKA the Receiver)
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	public ?int $user_id = null;

	/**
	 * The ID of the user who referred this user. (AKA the Sender)
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	public ?int $referrer_id = null;

	/**
	 * The code used for the referral
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $referral_code = null;

	/**
	 * The date when the referral converted (User placed a bid)
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $converted_date = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $referral_id
	 *
	 * @return ?Referral
	 */
	public function __construct( ?int $referral_id = null ) {
		if ( is_null( $referral_id ) ) {
			return $this;
		}

		$this->referral_id = $referral_id;

		return $this->get();
	}

	/**
	 * Initialize the Referral object.
	 *
	 * @return ?Referral
	 */
	public function get(): ?Referral {
		$referral = get_post( $this->get_id() );

		if ( ! $referral instanceof WP_Post ) {
			return null;
		}

		$this->post = $referral;

		return $this;
	}

	/**
	 * Get the Post Object for the Referral
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_Post
	 */
	public function get_post(): \WP_Post {
		return $this->post;
	}

	/**
	 * Get the ID for the Referral
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_id(): ?int {
		return $this->referral_id;
	}

	/**
	 * Set the Referral Code
	 *
	 * @since 1.0.0
	 *
	 * @param ?string $code
	 *
	 * @return bool|int
	 */
	public function set_code( ?string $code ) : bool|int {
		if ( ! $code ) {
			return false;
		}

		$this->referral_code = $code;

		if ( $this->get_id() ) {
			return update_post_meta( $this->get_id(), Referrals::REFERRAL_CODE_META_KEY, $this->referral_code );
		}

		return true;
	}

	/**
	 * Get the referral code used for the referral.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_code(): ?string {
		if ( $this->referral_code ) {
			return $this->referral_code;
		}

		$referral_code = get_post_meta( $this->get_id(), Referrals::REFERRAL_CODE_META_KEY, true );

		if ( $referral_code ) {
			$this->referral_code = $referral_code;
			return $this->referral_code;
		}

		return null;
	}

	/**
	 * Get the date the referral was created
	 *
	 * @since 1.0.0
	 *
	 * @param string $format
	 *
	 * @return string
	 */
	public function get_created_date( string $format = 'Y-m-d H:i:s' ): string {
		return wp_date(
			$format,
			get_the_date( 'U', $this->post ),
			new \DateTimeZone( 'GMT' )
		);
	}

	/**
	 * Mark the referral as converted.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function convert( int $auction_id, int $order_id ): void {
		$this->converted_date = current_time( 'mysql', true );

		// Update Converted Date
		update_post_meta( $this->get_id(), Referrals::CONVERTED_DATE_META_KEY, $this->converted_date );

		// Add additional Referral Meta
		update_post_meta( $this->get_id(), self::AUCTION_ID_META_KEY, $auction_id );
		update_post_meta( $this->get_id(), self::ORDER_ID_META_KEY, $order_id );
	}

	/**
	 * Get the date the referral was converted
	 *
	 * @since 1.0.0
	 *
	 * @param string $format
	 *
	 * @return ?string
	 */
	public function get_converted_date( string $format = 'Y-m-d H:i:s' ): ?string {
		if ( $this->converted_date ) {
			return goodbids()->utilities->format_date_time( $this->converted_date, $format );
		}

		$converted_date = get_post_meta( $this->get_id(), Referrals::CONVERTED_DATE_META_KEY, true );

		if ( ! $converted_date ) {
			return null;
		}

		$this->converted_date = $converted_date;

		return goodbids()->utilities->format_date_time( $this->converted_date, $format );
	}

	/**
	 * Check if the referral has been converted.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_converted(): bool {
		return (bool) $this->get_converted_date();
	}

	/**
	 * Set the Referrer ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $referrer_id
	 *
	 * @return int|bool
	 */
	public function set_referrer_id( int $referrer_id ): int|bool {
		$referrer = get_user_by( 'ID', $referrer_id );

		if ( ! $referrer ) {
			_doing_it_wrong( __METHOD__, 'Invalid Referrer User ID.', '6.4.2' );
			return false;
		}

		$this->referrer_id = $referrer_id;

		if ( $this->get_id() ) {
			return update_post_meta( $this->get_id(), Referrals::REFERRER_ID_META_KEY, $this->referrer_id );
		}

		return true;
	}

	/**
	 * Returns the referring user ID.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_referrer_id(): ?int {
		if ( $this->referrer_id ) {
			return $this->referrer_id;
		}

		$referrer_id = get_post_meta( $this->referrer_id, Referrals::REFERRER_ID_META_KEY, true );

		if ( ! $referrer_id ) {
			return null;
		}

		$this->referrer_id = $referrer_id;

		return $this->referrer_id;
	}

	/**
	 * Get the referring user object
	 *
	 * @since 1.0.0
	 *
	 * @return ?WP_User
	 */
	public function get_referrer(): ?WP_User {
		$referrer_id = $this->get_referrer_id();
		if ( ! $referrer_id ) {
			return null;
		}

		$referrer = get_user_by( 'ID', $referrer_id );

		if ( ! $referrer ) {
			return null;
		}

		return $referrer;
	}

	/**
	 * Set the User ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return int|bool
	 */
	public function set_user_id( int $user_id ): int|bool {
		$user = get_user_by( 'ID', $user_id );

		if ( ! $user ) {
			_doing_it_wrong( __METHOD__, 'Invalid User ID.', '6.4.2' );
			return false;
		}

		$this->user_id = $user_id;

		if ( $this->get_id() ) {
			return update_post_meta( $this->get_id(), Referrals::USER_ID_META_KEY, $this->user_id );
		}

		return true;
	}

	/**
	 * Return the referred User ID.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_user_id(): ?int {
		if ( $this->user_id ) {
			return $this->user_id;
		}

		$user_id = get_post_meta( $this->get_id(), Referrals::USER_ID_META_KEY, true );

		if ( ! $user_id ) {
			return null;
		}

		$this->user_id = $user_id;

		return $this->user_id;
	}

	/**
	 * Get the referred User object
	 *
	 * @since 1.0.0
	 *
	 * @return ?WP_User
	 */
	public function get_user(): ?WP_User {
		$user_id = $this->get_user_id();
		if ( ! $user_id ) {
			return null;
		}

		$user = get_user_by( 'ID', $user_id );

		if ( ! $user ) {
			return null;
		}

		return $user;
	}

	/**
	 * Save the Referral to the database.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function save(): bool {
		$referrer = $this->get_referrer();
		$user     = $this->get_user();

		if ( ! $referrer || ! $user ) {
			_doing_it_wrong( __METHOD__, 'Missing Referrer or User.', '6.4.2' );
			return false;
		}

		$title = sprintf(
			'%s referred %s',
			esc_html( $referrer->display_name ),
			esc_html( $user->display_name )
		);

		$referral_id = wp_insert_post(
			[
				'post_type'   => goodbids()->referrals->get_post_type(),
				'post_title'  => $title,
				'post_status' => 'publish',
				'post_author' => 1,
			]
		);

		if ( is_wp_error( $referral_id ) ) {
			Log::error( $referral_id->get_error_message(), [ 'referral' => $this ] );
			return false;
		}

		$this->referral_id = $referral_id;

		// Associate the Referrer ID with the User.
		goodbids()->referrals->set_referrer( $this->user_id, $this->referrer_id );

		// Set Referral Post Meta.
		update_post_meta( $this->get_id(), Referrals::REFERRER_ID_META_KEY, $this->referrer_id );
		update_post_meta( $this->get_id(), Referrals::USER_ID_META_KEY, $this->user_id );

		if ( $this->referral_code ) {
			update_post_meta( $this->get_id(), Referrals::REFERRAL_CODE_META_KEY, $this->referral_code );
		}

		return true;
	}
}
