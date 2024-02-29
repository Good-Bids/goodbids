<?php
/**
 * General Network Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

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
		$this->logs       = new Logs();
	}
}
