<?php
/**
 * Custom React Blocks
 *
 * @package GoodBids
 * @since 1.0.0
 */

namespace GoodBids\Blocks;

/**
 * Main Blocks Class
 *
 * @since 1.0.0
 */
class Blocks {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $base_dir = 'build/blocks';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $namespace = 'goodbids';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Register Custom Blocks.
		$this->register_blocks();
	}

	/**
	 * Register Custom React Blocks
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_blocks() : void {
		add_action(
			'init',
			function(): void {

				$blocks = [
					'bidding' => [
						'post_type' => [ goodbids()->auctions->get_post_type() ],
					],
				];

				foreach ( $blocks as $block => $config ) {
					$block_path = GOODBIDS_PLUGIN_PATH . $this->base_dir . '/' . $block;

					if ( ! file_exists( $block_path ) ) {
						continue;
					}

					register_block_type( $block_path );

					add_action(
						'wp_enqueue_scripts',
						function () use ( $block, $block_path ): void {
							$script_args = include $block_path . '/index.asset.php';

							wp_enqueue_script(
								$this->namespace . '-block-' . $block,
								GOODBIDS_PLUGIN_URL . $this->base_dir . '/' . $block . '/index.js',
								$script_args['dependencies'],
								$script_args['version']
							);
						}
					);

					if ( empty( $config['post_type'] ) ) {
						continue;
					}

					// Restrict blocks to specific post types.
					add_filter(
						'allowed_block_types_all',
						function ( $allowed_block_types, $context ) use ( $block, $config ) {
							if ( in_array( get_post_type(), $config['post_type'], true ) ) {
								return $allowed_block_types;
							}

							if ( is_array( $allowed_block_types ) ) {
								// Remove the block from the allowed blocks.
								return array_diff( $allowed_block_types, [ $this->namespace . '/' . $block ] );
							}

							// This probably is wrong, and needs to be an array of ALL blocks EXCEPT the one we want to remove.
							return $allowed_block_types;
						},
						10,
						2
					);
				}
			}
		);
	}
}
