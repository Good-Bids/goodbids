<?php
/**
 * Admin Functionality
 *
 * @package GoodBids
 * @since 1.0.0
 */

namespace GoodBids\Admin;

/**
 * Admin Main Class
 */
class Admin {

	/**
	 * @since 1.0.0
	 * @var Assets
	 */
	public Assets $assets;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->assets = new Assets();
	}
}
