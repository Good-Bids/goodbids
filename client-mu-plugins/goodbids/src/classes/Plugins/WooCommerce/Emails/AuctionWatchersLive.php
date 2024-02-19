<?php
/**
 * Auction Watchers Live: Email the users that are watching when an Auction goes live.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

use GoodBids\Auctions\Auction;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Watchers Live extend the custom BaseEmail class
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionWatchersLive extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_watchers_live';
		$this->title          = __( 'Auction Watchers Live', 'goodbids' );
		$this->description    = __( 'Email the users that are watching when an Auction goes live.', 'goodbids' );
		$this->template_html  = 'emails/auction-watchers-live.php';
		$this->template_plain = 'emails/plain/auction-watchers-live.php';
	}

	/**
	 * Initialize custom vars.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_vars(): void {
		// Custom Field Vars
		$this->add_email_var( 'button_text', $this->get_button_text() );
	}

	/**
	 * Initialize custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_fields(): void {
		/* translators: %s: list of placeholders */
		$placeholder_text  = sprintf( __( 'Available placeholders: %s', 'goodbids' ), '<code>' . esc_html( implode( '</code>, <code>', array_keys( $this->placeholders ) ) ) . '</code>' );

		// Custom Fields
		$this->set_form_field(
			'button_text',
			[
				'title'       => __( 'Bid Button Text', 'woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $placeholder_text,
				'placeholder' => $this->get_default_button_text(),
				'default'     => '',
			]
		);
	}

	/**
	 * Get email subject.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_default_subject(): string {
		return sprintf(
			// translators: %1$s: Site Name, %2$s: Auction Title
			__( '%1$s %2$s is live', 'goodbids' ),
			'{site_title}',
			'{auction.title}'
		);
	}

	/**
	 * Get email heading.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_default_heading(): string {
		return __( 'Ready to GOODBID?', 'goodbids' );
	}

	/**
	 * Get button text
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_default_button_text(): string {
		return __( 'Bid Now', 'goodbids' );
	}

	/**
	 * Get the button text.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_button_text(): string {
		/**
		 * Filter the button text.
		 * @since 1.0.0
		 *
		 * @param string $button_text The button text.
		 * @param object $object The object.
		 * @param object $this The email object.
		 */
		return apply_filters(
			'goodbids_button_text_' . $this->id,
			$this->format_string(
				$this->get_option( 'button_text', $this->get_default_button_text() )
			),
			$this->object,
			$this
		);
	}

	/**
	 * Trigger the Email
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $object
	 * @param ?int  $user_id
	 *
	 * @return void
	 */
	public function trigger( mixed $object = null, ?int $user_id = null ): void{
		if ( ! $object instanceof Auction ) {
			return;
		}

		parent::trigger( $object, $user_id );
	}
}
