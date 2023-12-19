<?php
/**
 * Patterns Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

/**
 * Class for patterns
 *
 * @since 1.0.0
 */
class Patterns {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const DEFAULT_NAMESPACE = 'goodbids';

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $patterns = [];

	/**
	 * Initialize Patterns
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->register_patterns();
	}

	/**
	 * Register Block Patterns
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_patterns(): void {
		add_action(
			'init',
			function (): void {
				// Register the GoodBids Pattern Category.
				register_block_pattern_category(
					self::DEFAULT_NAMESPACE,
					[
						'label' => __( 'GoodBids', 'goodbids' ),
					]
				);

				$auction_archive = [
					'name'       => 'template-archive-auction',
					'path'       => GOODBIDS_PLUGIN_PATH . 'views/patterns/template-archive-auction.php',
					'title'      => __( 'Archive Auction', 'goodbids' ),
					'categories' => [ 'goodbids' ],
					'keywords'   => [ 'non-profit', 'starter', 'archive' ],
					'inserter'   => true,
				];

				$this->patterns = apply_filters(
					'goodbids_block_patterns',
					[
						$auction_archive,
					]
				);

				foreach ( $this->patterns as $pattern ) {
					$this->register_pattern( $pattern );
				}
			}
		);
	}

	/**
	 * Register a block pattern
	 *
	 * @since 1.0.0
	 *
	 * @param array $pattern
	 *
	 * @return bool
	 */
	public function register_pattern( array $pattern ): bool {
		if ( ! file_exists( $pattern['path'] ) ) {
			return false;
		}

		$path      = $pattern['path'];
		$name      = $pattern['name'];
		$extension = pathinfo( $path, PATHINFO_EXTENSION );

		unset( $pattern['path'], $pattern['name'] );

		if ( str_starts_with( $path, 'http' ) ) {
			// TODO: Log warning.
			return false;
		}

		if ( empty( $pattern['categories'] ) ) {
			$pattern['categories'] = [ self::DEFAULT_NAMESPACE ];
		}

		if ( 'php' === $extension ) {
			ob_start();
			include $path;
			$content = ob_get_clean();
		} else {
			$content = file_get_contents( $path ); // phpcs:ignore
		}

		$pattern['content'] = $content;

		if ( ! str_contains( $name, '/' ) ) {
			$name = self::DEFAULT_NAMESPACE . '/' . $name;
		}

		return register_block_pattern( $name, $pattern );
	}
}
