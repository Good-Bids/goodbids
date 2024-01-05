<?php
/**
 * ACF Block
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\ACF;

/**
 * ACF Block Class
 *
 * @since 1.0.0
 */
class ACFBlock {

	/**
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private array $block = [];

	/**
	 * Initialize the block.
	 *
	 * @since 1.0.0
	 */
	public function __construct( array $block ) {
		$this->block = $block;
	}
}
