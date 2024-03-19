<?php
/**
 * General Network Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Nonprofits\Guide;

/**
 * Network Class
 *
 * @since 1.0.0
 */
class Network {

	/**
	 * Invoices
	 *
	 * @since 1.0.0
	 * @var ?Nonprofits
	 */
	public ?Nonprofits $nonprofits = null;

	/**
	 * Invoices
	 *
	 * @since 1.0.0
	 * @var ?Invoices
	 */
	public ?Invoices $invoices = null;

	/**
	 * Invoices
	 *
	 * @since 1.0.0
	 * @var ?Auctions
	 */
	public ?Auctions $auctions = null;

	/**
	 * Invoices
	 *
	 * @since 1.0.0
	 * @var ?Bidders
	 */
	public ?Bidders $bidders = null;

	/**
	 * Logs
	 *
	 * @since 1.0.0
	 * @var ?Logs
	 */
	public ?Logs $logs = null;

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Init Submodules.
		$this->nonprofits = new Nonprofits();
		$this->invoices   = new Invoices();
		$this->auctions   = new Auctions();
		$this->bidders    = new Bidders();
		$this->logs       = new Logs();

		// Setup API Endpoints.
		$this->setup_api_endpoints();
	}

	/**
	 * Register Auction REST API Endpoints
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function setup_api_endpoints(): void {
		add_action(
			'rest_api_init',
			function () {
				( new API\Publish() )->register_routes();
			}
		);
	}
}
