<?php
/**
 * Referral Generator Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users\Referrals;

/**
 * Class for Referral Generator
 *
 * @since 1.0.0
 */
class Generator {

	/**
	 * Initialize Referral Generator
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Generates a unique referral code.
	 *
	 * @param ?int $length
	 *
	 * @return string
	 */
	public function create( ?int $length = null ): string {
		if ( ! $length ) {
			$length = goodbids()->get_config( 'referrals.code-length' );
		}

		$unique = false;
		$code   = false;

		while ( ! $unique ) {
			$code = $this->new( $length );

			if ( $this->is_unique( $code ) ) {
				$unique = true;
			}
		}

		return $code;
	}

	/**
	 * Generate a random string for referral codes.
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	private function new( int $length ): string {
		return wp_generate_password( $length, false );
	}

	/**
	 * Check if the referral code is unique.
	 *
	 * @param string $code
	 *
	 * @return bool
	 */
	private function is_unique( string $code ): bool {
		return ! goodbids()->referrals->get_user_id_by_code( $code );
	}
}
