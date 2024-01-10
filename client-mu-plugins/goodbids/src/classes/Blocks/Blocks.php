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

				foreach ( $blocks as $block_dir => $config ) {
					$block_path = GOODBIDS_PLUGIN_PATH . $this->base_dir . '/' . $block_dir;

					if ( ! file_exists( $block_path ) ) {
						continue;
					}

					$block = register_block_type( $block_path );

					add_action(
						'wp_enqueue_scripts',
						function () use ( $block_dir, $block_path ): void {
							$script_args = include $block_path . '/index.asset.php';

							wp_enqueue_script(
								$this->namespace . '-block-' . $block_dir,
								GOODBIDS_PLUGIN_URL . $this->base_dir . '/' . $block_dir . '/index.js',
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
						function ( $allowed_block_types ) use ( $block, $config ) {
							if ( in_array( get_post_type(), $config['post_type'], true ) ) {
								return $allowed_block_types;
							}

							$disabled = [
								$block->name,
							];

							if ( ! is_array( $allowed_block_types ) ) {
								$allowed_block_types = array_keys( \WP_Block_Type_Registry::get_instance()->get_all_registered() );
							}

							// Remove the block from the allowed blocks.
							return array_values( array_diff( $allowed_block_types, $disabled ) );
						}
					);
				}
			}
		);
	}
}
