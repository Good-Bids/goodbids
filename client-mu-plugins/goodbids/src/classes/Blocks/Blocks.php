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
					register_block_type( GOODBIDS_PLUGIN_PATH . $this->base_dir . '/' . $block );

					add_action(
						'wp_enqueue_scripts',
						function () use ( $block ): void {
							$script_args = include GOODBIDS_PLUGIN_PATH . $this->base_dir . '/' . $block . '/index.asset.php';

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

					add_filter(
						'allowed_block_types',
						function ( $allowed_block_types, $post ) use ( $block, $config ) {
							if ( in_array( $post->post_type, $config['post_type'], true ) ) {
								return $allowed_block_types;
							}

							return [ $this->namespace . '/' . $block ];
						},
						10,
						2
					);
				}
			}
		);
	}
}
