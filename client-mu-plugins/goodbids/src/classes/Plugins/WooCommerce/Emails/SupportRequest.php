<?php
/**
 * Support Request Email
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

use GoodBids\Frontend\Request;
use GoodBids\Utilities\Log;

defined( 'ABSPATH' ) || exit;

/**
 * Support Request email
 *
 * @since 1.0.0
 * @extends Email
 */
class SupportRequest extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_support_request';
		$this->title          = __( 'Support Request', 'goodbids' );
		$this->description    = __( 'Notification email sent to all site admins when a new support request is received.', 'goodbids' );
		$this->template_html  = 'emails/support-request.php';
		$this->template_plain = 'emails/plain/support-request.php';
		$this->admin_email    = true;

		$this->trigger_on_new_support_request();
	}

	/**
	 * Trigger this email when a new Support Request is Received.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function trigger_on_new_support_request(): void {
		add_action(
			'goodbids_support_request_received',
			function ( int $request_id ) {
				$request = new Request( $request_id );
				$this->send_to_admins( $request );
			}
		);
	}

	/**
	 * Get email subject.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_subject(): string {
		return sprintf(
			/* translators: %1$s: site title */
			__( '[%1$s] Support Request Received', 'goodbids' ),
			'{site_title}'
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
			/* translators: %1$s: Requesting user's Username */
			__( 'New Support Request from %1$s', 'goodbids' ),
			'{user.name}',
			'{site_title}'
		);
	}
}
