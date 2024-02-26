<?php
/**
 * Vite Implementation
 *
 * Inspired from:
 *
 * @source https://github.com/andrefelipe/vite-php-setup
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

use GoodBids\Core;

/**
 * This class handles loading Vite assets.
 */
class Vite {

	/**
	 * @var string
	 */
	private string $site_url;

	/**
	 * @since 1.0.0
	 * @var int
	 */
	private int $port;

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $dev_server;

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $dist_url;

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $dist_path;

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $entries = [];

	/**
	 * @since 1.0.0
	 * @var bool
	 */
	private bool $initialized = false;

	/**
	 * Set up vars and hooks
	 */
	public function __construct() {
		$this->site_url   = get_site_url();
		$this->port       = 5173;
		$this->dev_server = "$this->site_url:$this->port";

		$this->dist_url  = GOODBIDS_PLUGIN_URL . 'dist/';
		$this->dist_path = GOODBIDS_PLUGIN_PATH . 'dist/';

		$this->entries['default'] = 'main.tsx';
		$this->entries['admin']   = 'admin.tsx';
		$this->entries['editor']  = 'editor.tsx';

		add_action( 'wp_head', [ $this, 'init' ] );

		add_action(
			'admin_head',
			function () {
				$screen = get_current_screen();
				if ( $screen->is_block_editor ) {
					// $this->init(); // This breaks the block editor styles.
					$this->init( 'editor' );
				}
			}
		);

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_assets' ], 15 );

		add_filter( 'script_loader_tag', [ $this, 'script_loader' ], 10, 3 );
		add_filter( 'style_loader_tag', [ $this, 'style_loader' ], 10, 4 );

		// Setup color palette.
		add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ] );

		add_action( 'acf/input/admin_footer', [ $this, 'acf_color_palette' ] );
	}

	/**
	 * Initialize
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry Entry key.
	 *
	 * @return void
	 */
	public function init( string $entry = '' ): void {
		if ( ! $this->get_entry( $entry ) ) {
			return;
		}

		$this->initialized = true;

		echo $this->vite( $this->get_entry( $entry ) );
	}

	/**
	 * Add a new entry point file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry The entry point file.
	 * @param string $key   The callable array key index.
	 *
	 * @return void
	 */
	public function add_entry( string $entry, string $key = '' ): void {
		if ( ! $key ) {
			$key = rtrim( $entry, '.js' );
		}

		if ( array_key_exists( $key, $this->entries ) ) {
			// Log: Entry key already exists.
			return;
		}

		$this->entries[ $key ] = $entry;
	}

	/**
	 * Get entry point file by key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry The entry point key.
	 *
	 * @return string|false
	 */
	public function get_entry( string $entry = '' ): string|false {
		if ( ! $entry ) {
			$entry = 'default';
		}

		if ( array_key_exists( $entry, $this->entries ) ) {
			return $this->entries[ $entry ];
		}

		return false;
	}

	/**
	 * Prints all the html entries needed for Vite
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry The entry point file.
	 *
	 * @return string
	 */
	public function vite( string $entry ): string {
		if ( Core::is_local_env() ) {
			$scripts = [
				"<script type=\"module\">
					import RefreshRuntime from 'http://localhost:5173/@react-refresh'
					RefreshRuntime.injectIntoGlobalHook(window)
					window.\$RefreshReg\$ = () => {}
					window.\$RefreshSig\$ = () => (type) => type
					window.__vite_plugin_react_preamble_installed__ = true
			  	</script>",
				"<script type=\"module\" src=\"http://localhost:5173/@vite/client\"></script>",
				"<script type=\"module\" src=\"http://localhost:5173/$entry\"></script>",
			];

			return implode( PHP_EOL, $scripts );
		}

		// TODO this will need to be updated to work with vendor files
		// Or we can just turn off vendor chunks
		return implode(
			PHP_EOL,
			[
				$this->js( $entry ),
				$this->imports( $entry ),
				$this->css( $entry ),
			]
		);
	}

	/**
	 * Helpers to print tags
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry The entry point file.
	 *
	 * @return string
	 */
	private function js( string $entry ): string {
		$url = $this->get_asset_url( $entry );

		if ( ! $url ) {
			return '';
		}

		return sprintf( '<script type="module" crossorigin src="%s"></script>', esc_url( $url ) );
	}

	/**
	 * Helper to print preload URLs.
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry The entry point file.
	 *
	 * @return string
	 */
	private function imports( string $entry ): string {
		$res = '';

		foreach ( $this->get_imports( $entry ) as $url ) {
			$res .= sprintf( '<link rel="modulepreload" href="%s">', esc_url( $url ) );
		}

		return $res;
	}

	/**
	 * Adjust theme style tags.
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry The entry point file.
	 *
	 * @return string
	 */
	private function css( string $entry ): string {
		$tags = '';

		foreach ( $this->get_css( $entry ) as $url ) {
			$tags .= sprintf( '<link rel="stylesheet" href="%s">', esc_url( $url ) );
		}

		return $tags;
	}

	/**
	 * Helper to locate files
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_manifest(): array {
		$manifest = $this->dist_path . '/.vite/manifest.json';

		if ( ! file_exists( $manifest ) ) {
			add_action(
				'admin_notices',
				function () {
					printf(
						'<div class="notice notice-warning is-dismissible">
						<p>%s</p>
					</div>',
						esc_html__( 'Manifest.json file is missing. Run npm run build...', 'goodbids' )
					);
				}
			);

			return [];
		}

		$content = file_get_contents( $manifest ); // phpcs:ignore

		if ( ! $content ) {
			die( 'Error: The manifest.json file is empty.' );
		}

		return json_decode( $content, true );
	}

	/**
	 * Get Asset URL
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry The entry point file.
	 *
	 * @return string
	 */
	private function get_asset_url( string $entry ): string {
		$manifest = $this->get_manifest();

		if ( ! isset( $manifest[ $entry ] ) ) {
			die(
				sprintf(
					'% %',
					esc_html__( 'Could not find entry in manifest for', 'goodbids' ),
					esc_html( $entry )
				)
			);
		}

		return $this->dist_url . $manifest[ $entry ]['file'];
	}

	/**
	 * Get import URLs.
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry The entry point file.
	 *
	 * @return array
	 */
	private function get_imports( string $entry ): array {
		$urls     = [];
		$manifest = $this->get_manifest();

		if ( ! empty( $manifest[ $entry ]['imports'] ) ) {
			foreach ( $manifest[ $entry ]['imports'] as $imports ) {
				$urls[] = $this->dist_url . $manifest[ $imports ]['file'];
			}
		}

		return $urls;
	}

	/**
	 * Get CSS URLs
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry The entry point file.
	 *
	 * @return array
	 */
	function get_css( string $entry ): array {
		$urls     = [];
		$manifest = $this->get_manifest();

		if ( ! empty( $manifest[ $entry ]['css'] ) ) {
			foreach ( $manifest[ $entry ]['css'] as $file ) {
				$urls[] = $this->dist_url . $file;
			}
		}

		return $urls;
	}

	/**
	 * Adjust theme script tags.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tag    The generated tag (markup).
	 * @param string $handle The script handle.
	 * @param string $src    The script source.
	 *
	 * @return string
	 */
	public function script_loader( string $tag, string $handle, string $src ): string {
		if ( ! str_contains( $handle, 'goodbids-script-' ) ) {
			return $tag;
		}

		return sprintf( '<script type="module" crossorigin src="%s"></script>', esc_url( $src ) );
	}

	/**
	 * Adjust theme style tags.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tag    The generated tag (markup).
	 * @param string $handle The style handle.
	 * @param string $href   The style URL.
	 * @param string $media  The style media.
	 *
	 * @return string
	 */
	public function style_loader( string $tag, string $handle, string $href, string $media ): string {
		if ( ! str_contains( $handle, 'goodbids-style-preload-' ) ) {
			return $tag;
		}

		return sprintf(
			'<link rel="modulepreload" href="%s" media="%s">',
			esc_url( $href ),
			esc_attr( $media )
		);
	}

	/**
	 * Enqueue assets in Admin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_assets(): void {
		$entry = 'admin';

		if ( ! $this->get_entry( $entry ) ) {
			return;
		}

		$i = 0;
		foreach ( $this->get_css( $this->get_entry( $entry ) ) as $url ) {
			++$i;
			wp_enqueue_style( 'goodbids-style-admin-' . $i, $url, [], goodbids()->get_version() );
		}

		$url = $this->get_asset_url( $this->get_entry( $entry ) );

		if ( $url ) {
			$handle = 'goodbids-script-admin';
			wp_register_script( $handle, $url, [ 'jquery' ], goodbids()->get_version(), true );

			do_action( 'goodbids_enqueue_admin_script', $handle );

			wp_enqueue_script( $handle );
		}
	}

	/**
	 * Enqueue assets for block editor
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry The entry point file.
	 *
	 * @return void
	 */
	public function block_assets( string $entry = '' ): void {
		if ( ! $entry ) {
			$screen = get_current_screen();
			$entry  = $screen && 'site-editor' === $screen->base ? 'default' : 'editor';
		}

		if ( ! $this->get_entry( $entry ) ) {
			return;
		}

		$i    = 0;
		$file = $this->get_entry( $entry );

		$css_dependencies = [
			'wp-block-library-theme',
			'wp-block-library',
		];

		foreach ( $this->get_css( $file ) as $url ) {
			++$i;
			wp_enqueue_style( 'goodbids-style-editor-' . $i, $url, $css_dependencies, '1.0' );
		}

		foreach ( $this->get_imports( $file ) as $url ) {
			++$i;
			wp_enqueue_style( 'goodbids-style-preload-editor-' . $i, $url, $css_dependencies, '1.0' );
		}

		// Theme Gutenberg blocks JS.
		$js_dependencies = [
			'wp-block-editor',
			'wp-blocks',
			'wp-editor',
			'wp-components',
			'wp-compose',
			'wp-data',
			'wp-element',
			'wp-hooks',
			'wp-i18n',
		];

		$script_url = $this->get_asset_url( $file );
		$handle     = 'goodbids-script-editor-main';

		if ( Core::is_local_env() ) {
			$vite_client = $this->dev_server . '/@vite/client';
			$vite_entry  = $this->dev_server . '/' . $entry;

			wp_register_script(
				'goodbids-script-editor-vite-client',
				$vite_client,
				$js_dependencies,
				goodbids()->get_version(),
				true
			);

			do_action( 'goodbids_enqueue_editor_script', $handle );

			wp_enqueue_script(
				$handle,
				$vite_entry,
				[ 'goodbids-script-editor-vite-client' ],
				goodbids()->get_version(),
				true
			);
		} else {
			wp_register_script(
				$handle,
				$script_url,
				$js_dependencies,
				goodbids()->get_version(),
				true
			);

			do_action( 'goodbids_enqueue_editor_script', $handle );

			wp_enqueue_script( $handle );
		}
	}

	/**
	 * Get Tailwind config array, if available.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_tailwind_config(): array {
		$tw_cfg_path = $this->dist_path . '/config/tailwind.json';

		if ( ! file_exists( $tw_cfg_path ) ) {
			return [];
		}

		$tw_config = file_get_contents( $tw_cfg_path ); // phpcs:ignore

		if ( ! $tw_config ) {
			return [];
		}

		return json_decode( $tw_config, true );
	}

	/**
	 * Use Tailwind config colors in Gutenberg.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function after_setup_theme(): void {
		add_editor_style( $this->get_css( $this->get_entry( 'editor' ) ) );
		add_editor_style( $this->get_css( $this->get_entry() ) );

		if ( ! $this->get_tailwind_config() ) {
			return;
		}

		add_theme_support( 'editor-color-palette', $this->get_color_palette() );
	}

	/**
	 * Generate Gutenberg Color palette from Tailwind config.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_color_palette(): array {
		$config  = $this->get_tailwind_config();
		$palette = [];

		if ( ! $config || empty( $config['theme'] ) ) {
			return apply_filters( 'theme_palette', $palette );
		}

		$colors = $config['theme']['editorColors'] ?? $config['theme']['colors'];

		foreach ( $colors as $slug => $color ) {
			$name = ucwords( $slug );

			if ( is_array( $color ) ) {
				foreach ( $color as $variation => $hex ) {
					if ( ! is_string( $hex ) ) {
						continue;
					}

					$palette[] = [
						'name'  => $name . ' ' . $variation,
						'slug'  => $slug . '-' . $variation,
						'color' => $hex,
					];
				}
			} elseif ( is_string( $color ) ) {
				$palette[] = [
					'name'  => $name,
					'slug'  => $slug,
					'color' => $color,
				];
			}
		}

		return apply_filters( 'theme_palette', $palette );
	}

	/**
	 * Add color palette to ACF.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function acf_color_palette(): void {
		$palette = $this->get_color_palette();

		if ( ! $palette ) {
			return;
		}

		$colors = [];
		foreach ( $palette as $color ) {
			$colors[] = $color['color'];
		}
		?>
		<script type="text/javascript">
			(function($){
				if ( 'undefined' === typeof acf ) {
					return;
				}

				acf.add_filter( 'color_picker_args', function( args, $field ) {
					args.palettes = [ '<?php echo implode( '\',\'', $colors ); ?>' ];
					return args;
				});
			})(jQuery);
		</script>
		<?php
	}
}
