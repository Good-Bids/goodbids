<?php
/**
 * Generate Documentation
 *
 * @package WPDocsGenerator
 */

namespace Viget\ComposerScripts\WPDocsGenerator;

use Viget\ComposerScripts\WPDocsGeneratorScript;

/**
 * WPDocsGenerator Class
 */
class WPDocsGenerator {

	/**
	 * @var ?WPDocsGenerator
	 */
	public static ?WPDocsGenerator $instance = null;

	/**
	 * @var ?WPDocsGeneratorScript
	 */
	private ?WPDocsGeneratorScript $script = null;

	/**
	 * @var ?Parser
	 */
	private ?Parser $parser = null;

	/**
	 * @var DocItem[]
	 */
	private array $objects = [];

	/**
	 * @var array
	 */
	private array $config = [];

	/**
	 * Initialize WPDocsGenerator
	 *
	 * @return WPDocsGenerator
	 */
	public static function getInstance(): WPDocsGenerator
	{
		if ( null === self::$instance ) {
			self::$instance = new WPDocsGenerator();
		}

		return self::$instance;
	}

	/**
	 * Initialize this class
	 *
	 * @param WPDocsGeneratorScript $script
	 * @param array $config
	 * @return void
	 */
	public function init( WPDocsGeneratorScript $script, array $config ): void
	{
		$this->script = $script;
		$this->config = $config;
		$this->parser = new Parser();
	}

	/**
	 * Generate Docs.
	 * @return void
	 */
	public function generate(): void
	{
		$dir = $this->config['source'];

		$this->traverse( $dir );

		// TODO: Generate the docs.
	}

	/**
	 * @param string $path
	 * @return void
	 */
	private function traverse( string $path ): void
	{
		$path    = str_replace( '//', '/', $path );
		$dirname = basename( $path );

		// Ignore directories based on config.
		if ( in_array( $dirname, $this->config['ignore'] ) ) {
			return;
		}

		// Make sure it exists.
		if ( ! is_dir( $path ) ) {
			return;
		}

		// Loop through all PHP files.
		$files = glob( $path . '*.php' );

		if ( ! empty( $files ) ) {
			foreach ( $files as $file ) {
				$objects = $this->parser->parse( $file );

				if ( is_null( $objects ) ) {
					$this->script::writeError( $this->parser->error );
					continue;
				}

				if ( empty( $objects ) ) {
					continue;
				}

				$this->objects = array_merge( $this->objects, $objects );
			}
		}

		$subdirectories = glob( $path . '*/', GLOB_ONLYDIR );

		foreach ( $subdirectories as $subdirectory ) {
			$this->traverse( $subdirectory );
		}
	}
}
