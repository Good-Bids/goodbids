<?php
/**
 * User-specific Methods
 *
 * @package GoodBids
 * @since 1.0.0
 */

namespace GoodBids\Users;

/**
 * User Class
 *
 * @since 1.0.0
 */
class Users {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Update Email Content for User.
		$this->update_user_email_content();
	}

	/**
	 * Get User Email Addresses. If no user_id is provided, the current user is used.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return array
	 */
	public function get_emails( int $user_id = null ): array {
		$emails = [];

		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$user = get_user_by( 'id', $user_id );

		if ( ! $user ) {
			return $emails;
		}

		$emails[] = $user->user_email;

		$billing_email = get_user_meta( $user_id, 'billing_email', true );
		if ( $billing_email ) {
			$emails[] = $billing_email;
		}

		return $emails;
	}

	/**
	 * Add Terms and Conditions to User Emails
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function update_user_email_content(): void {
		add_filter(
			'wpmu_signup_user_notification_email',
			function ( $content ) {
				$content .= "\r\n" . goodbids()->sites->get_terms_conditions_text();
				return $content;
			},
			11
		);

		add_filter(
			'wp_new_user_notification_email',
			function ( $email ) {
				$email['message'] .= "\r\n" . goodbids()->sites->get_terms_conditions_text();
				return $email;
			},
			11
		);
	}
}
