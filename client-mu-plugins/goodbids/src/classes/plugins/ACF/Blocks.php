<?php
/**
 * ACF Custom Blocks
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\ACF;

/**
 * ACF Blocks Class
 *
 * @since 1.0.0
 */
class Blocks {

	/**
	 * Default ACF Block prefix
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $block_prefix = 'acf';

	/**
	 * Register Blocks and Block Category
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->register_block_category();
		$this->register_blocks();
		$this->set_block_callback();
	}

	/**
	 * Register Custom Blocks
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_blocks() : void {
		add_action(
			'acf/init',
			function () {
				$blocks = $this->get_all_blocks();

				foreach ( $blocks as $block ) {
					$include = $block['path'] . '/block.php';

					// Autoload block.php within block directory
					if ( file_exists( $include ) ) {
						require $include;
					}

					register_block_type( $block['path'] . '/block.json' );
				}
			}
		);
	}

	/**
	 * Get All Available Blocks
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_all_blocks() : array {
		$blocks    = [];
		$locations = $this->get_block_locations();

		foreach ( $locations as $location ) {
			$group = glob( trailingslashit( $location ) . '**/block.json' );

			foreach ( $group as $block_path ) {
				$block = json_decode( wpcom_vip_file_get_contents( $block_path ), true );

				// Add path as a property.
				$block['path'] = dirname( $block_path );

				$blocks[] = $block;
			}
		}

		return $blocks;
	}

	/**
	 * Get block array
	 *
	 * @since 1.0.0
	 *
	 * @param string $block_name
	 *
	 * @return array|false
	 */
	public function get_block( string $block_name ) : array|false {
		$block_path = $this->get_block_location( $block_name, 'json' );

		if ( ! $block_path ) {
			return false;
		}

		$block = json_decode( wpcom_vip_file_get_contents( $block_path ), true );

		$block['path'] = dirname( $block_path );

		return $block;
	}

	/**
	 * Get locations where custom blocks can be found.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_block_locations() : array {
		return apply_filters(
			'goodbids_block_locations',
			[
				GOODBIDS_PLUGIN_PATH . '/blocks',
			]
		);
	}

	/**
	 * Get path to block by name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $block_name
	 * @param string $return
	 *
	 * @return false|string
	 */
	public function get_block_location( string $block_name, string $return = 'directory' ) : false|string {
		// Only allow ACF blocks
		if ( str_contains( $block_name, '/' ) && ! str_starts_with( $block_name, $this->block_prefix . '/' ) ) {
			return false;
		}

		$block_name = str_replace( $this->block_prefix . '/', '', $block_name );
		$blocks     = $this->get_all_blocks();

		foreach ( $blocks as $block ) {
			if ( $block_name !== $block['name'] ) {
				continue;
			}

			if ( 'json' === $return ) {
				return $block['path'] . '/block.json';
			}

			return $block['path'];
		}

		return false;
	}

	/**
	 * Register a custom Block Category.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function register_block_category() : void {
		add_filter(
			'block_categories_all',
			function ( array $categories ) : array {
				return array_merge(
					[
						[
							'slug'  => 'goodbids',
							'title' => __( 'GoodBids', 'goodbids' ),
						]
					],
					$categories
				);
			}
		);
	}

	/**
	 * Automatically add block render callbacks for custom blocks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_block_callback() : void {
		add_filter(
			'block_type_metadata',
			function ( array $metadata ) : array { // phpcs:ignore
				if ( ! function_exists( '\acf_is_acf_block_json' ) || ! \acf_is_acf_block_json( $metadata ) ) {
					return $metadata;
				}

				if ( ! empty( $metadata['acf']['renderCallback'] ) || ! empty( $metadata['acf']['renderTemplate'] ) || empty( $metadata['name'] ) ) {
					return $metadata;
				}

				$metadata['acf']['renderCallback'] = function( array $block ) : void {
					$block_name    = str_replace( $this->block_prefix . '/', '', $block['name'] );
					$block['slug'] = sanitize_title( $block_name );
					if ( empty( $block['path'] ) ) {
						$block['path'] = $this->get_block_location( $block_name );
					}
					$render = $block['path'] . '/render.php';

					if ( ! file_exists( $render ) ) {
						// TODO: Log error.
						return;
					}

					require $render;
				};

				return $metadata;
			},
			5
		);
	}

	/**
	 * Render the ACF block attributes
	 *
	 * @since 1.0.0
	 *
	 * @param array $block
	 * @param string $addl_class
	 * @param array $attr
	 *
	 * @return void
	 */
	public function block_attr( array $block, string $addl_class = '', array $attr = [] ) : void {
		$extra = [
			'id'    => $this->get_block_id( $block ),
			'class' => $this->get_block_class( $block, $addl_class ),
		];

		if ( ! empty( $block['support']['jsx'] ) ) {
			$extra['data-supports-jsx'] = 'true';
		}

		$extra = array_merge( $extra, $attr );

		// Attributes are escaped within get_block_wrapper_attributes.
		echo get_block_wrapper_attributes( $extra ); // phpcs:ignore

		do_action( 'goodbids_block_attr', $block );
	}

	/**
	 * Get unique block ID.
	 *
	 * @since 1.0.0
	 *
	 * @param array $block
	 *
	 * @return string
	 */
	private function get_block_id( array $block ) : string {
		if ( ! empty( $block['anchor'] ) ) {
			return $block['anchor'];
		}

		$prefix = str_replace( $this->block_prefix . '/', '', $block['name'] );

		if ( empty( $block['id'] ) ) {
			return $prefix . '_' . uniqid();
		}

		return $prefix . '_' . $block['id'];
	}

	/**
	 * Get custom block classes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $block
	 * @param string $addl_class
	 *
	 * @return string
	 */
	private function get_block_class( array $block, string $addl_class = '' ) : string {
		$class = array_merge(
			[
				str_replace( $this->block_prefix . '/', '', $block['name'] ),
			],
			explode( ' ', $addl_class )
		);

		if ( ! empty( $block['className'] ) ) {
			$class[] = $block['className'];
		}

		if ( ! empty( $block['align'] ) ) {
			$class[] = 'align' . $block['align'];
		}

		if ( ! empty( $block['align_content'] ) ) {
			$class[] = 'align-content-' . $block['align_content'];
		}

		$class = apply_filters( 'goodbids_block_class', $class, $block );

		return trim( implode( ' ', array_unique( $class ) ) );
	}
}
