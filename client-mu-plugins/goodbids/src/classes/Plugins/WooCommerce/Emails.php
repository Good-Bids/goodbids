<?php
/**
 * WooCommerce Emails Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use GoodBids\Plugins\WooCommerce\Emails\AuctionClosed;
use GoodBids\Plugins\WooCommerce\Emails\AuctionFreeBidUsed;
use GoodBids\Plugins\WooCommerce\Emails\AuctionIsLive;
use GoodBids\Plugins\WooCommerce\Emails\AuctionIsLiveAdmin;
use GoodBids\Plugins\WooCommerce\Emails\AuctionOutbid;
use GoodBids\Plugins\WooCommerce\Emails\AuctionPaidBidPlaced;
use GoodBids\Plugins\WooCommerce\Emails\AuctionRewardClaimed;
use GoodBids\Plugins\WooCommerce\Emails\AuctionRewardReminder;
use GoodBids\Plugins\WooCommerce\Emails\AuctionSummaryAdmin;
use GoodBids\Plugins\WooCommerce\Emails\AuctionWinnerConfirmation;
use GoodBids\Plugins\WooCommerce\Emails\FreeBidEarned;

/**
 * Class for Email Methods
 *
 * @since 1.0.0
 */
class Emails {

	/**
	 * Custom Email Classes
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private array $email_classes = [];

	/**
	 * Initialize Email
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Create the custom email classes array.
		$this->init_custom_emails();

		// Add email notifications.
		$this->register_custom_emails();

		// Adding Custom Email Styles
		$this->add_custom_email_styles();

		// Disable New Order Emails
		$this->disable_new_order_emails();

		// Disable Processing Order Emails
		$this->disable_processing_order_emails();
	}

	/**
	 * Initialize Custom Emails
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_custom_emails(): void {
		add_action(
			'init',
			function () {
				$this->email_classes = [
					'AuctionClosed'             => new AuctionClosed(),
					'AuctionFreeBidUsed'        => new AuctionFreeBidUsed(),
					'AuctionIsLive'             => new AuctionIsLive(),
					'AuctionIsLiveAdmin'        => new AuctionIsLiveAdmin(),
					'AuctionOutbid'             => new AuctionOutbid(),
					'AuctionPaidBidPlaced'      => new AuctionPaidBidPlaced(),
					'AuctionRewardClaimed'      => new AuctionRewardClaimed(),
					'AuctionRewardReminder'     => new AuctionRewardReminder(),
					'AuctionSummaryAdmin'       => new AuctionSummaryAdmin(),
					'AuctionWinnerConfirmation' => new AuctionWinnerConfirmation(),
					'FreeBidEarned'             => new FreeBidEarned(),
				];
			}
		);
	}

	/**
	 * Add a custom email to the list of emails WooCommerce
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_custom_emails(): void {
		add_filter(
			'woocommerce_email_classes',
			fn ( array $email_classes ): array => array_merge( $email_classes, $this->email_classes )
		);
	}

	/**
	 * Add custom email styles
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_custom_email_styles(): void {
		add_filter(
			'woocommerce_email_styles',
			function ( string $css ) {
				$custom_css = sprintf(
					'.button-wrapper {
						text-align: center;
						margin: 42px 0 12px 0 !important;
					}
					.button {
						display: inline-block;
						margin: 0 auto;
						background-color: %s;
						border-radius: 3px;
						color: #ffffff;
						padding: 8px 20px;
						text-decoration: none;
					}',
					esc_attr( get_option( 'woocommerce_email_base_color' ) )
				);

				return $css . $custom_css;
			},
			10
		);
	}

	/**
	 * Disable New Order Emails
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_new_order_emails(): void {
		add_filter( 'woocommerce_email_enabled_new_order', '__return_false' );

		add_filter(
			'woocommerce_email_get_option',
			function ( mixed $value, \WC_Email $email, mixed $original_value, string $key ) {
				if ( 'new_order' !== $email->id || 'enabled' !== $key ) {
					return $value;
				}

				$email->form_fields['enabled']['default'] = 'no';

				return 'no';
			},
			10,
			4
		);
	}

	/**
	 * Disable Processing Order Emails
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_processing_order_emails(): void {
		add_filter( 'woocommerce_enabled_customer_processing_order', '__return_false' );

		add_filter(
			'woocommerce_email_get_option',
			function ( mixed $value, \WC_Email $email, mixed $original_value, string $key ) {
				if ( 'customer_processing_order' !== $email->id || 'enabled' !== $key ) {
					return $value;
				}

				$email->form_fields['enabled']['default'] = 'no';

				return 'no';
			},
			10,
			4
		);
	}
}
