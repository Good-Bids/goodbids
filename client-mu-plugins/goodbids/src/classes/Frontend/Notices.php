<?php
/**
 * Front-end Notices
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

/**
 * Display Front-end Notices
 *
 * @since 1.0.0
 */
class Notices {

	/**
	 * The notice ID from gb-notice query arg.
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $notice_id = null;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->get_notice_id();

		$this->set_notice();
	}

	/**
	 * Grab the notice id from the query arg.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function get_notice_id(): void {
		if ( empty( $_REQUEST['gb-notice'] ) ) { // phpcs:ignore
			return;
		}

		$this->notice_id = trim( sanitize_text_field( wp_unslash( $_REQUEST['gb-notice'] ) ) ); // phpcs:ignore
	}

	/**
	 * Set the message based on the notice id.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_notice(): void {
		add_action(
			'init',
			function() {
				if ( ! $this->notice_id ) {
					return;
				}

				if ( 'not-authenticated-reward' === $this->notice_id ) {
					wc_add_notice( __( 'You must be logged in to checkout with Reward products.', 'goodbids' ), 'error' );
				} elseif ( 'auction-not-ended' === $this->notice_id ) {
					wc_add_notice( __( 'This auction has not ended.', 'goodbids' ), 'error' );
				} elseif ( 'not-auction-winner' === $this->notice_id ) {
					wc_add_notice( __( 'You are not the winner of this auction.', 'goodbids' ), 'error' );
				} elseif ( 'auction-not-found' === $this->notice_id ) {
					wc_add_notice( __( 'This reward is not associated with an auction.', 'goodbids' ), 'error' );
				}
			}
		);
	}
}
