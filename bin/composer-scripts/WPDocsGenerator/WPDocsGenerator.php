<?php
/**
 * Generate Documentation
 *
 * @package WPDocsGenerator
 */

namespace Viget\ComposerScripts\WPDocsGenerator;

use Viget\ComposerScripts\WPDocsGenerator\Builders\Builder;
use Viget\ComposerScripts\WPDocsGenerator\Builders\HtmlBuilder;
use Viget\ComposerScripts\WPDocsGenerator\Builders\MarkdownBuilder;
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
	 * @var DocItem[]
	 */
	private array $tree = [];

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

		// Parse the PHP files into objects.
		$this->parse( $dir );

		// Group objects together.
//		$this->collect();

		// Generate the docs.
		$this->build();

		// Report the results
		$this->finish();
	}

	/**
	 * @param string $path
	 * @return void
	 */
	private function parse( string $path ): void
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
				$relative = str_replace( $this->config['source'], basename( $this->config['source'] ) . '/', $file );
				$collection = $this->parser->parse( $file, $relative );

				if ( is_null( $collection ) ) {
					$this->script::writeError( $this->parser->error );
					continue;
				}

				if ( empty( $collection->objects ) ) {
					continue;
				}

				$this->objects = array_merge( $this->objects, $collection->objects );
				$this->tree    = array_merge( $this->tree, $collection->tree );
			}
		}

		$subdirectories = glob( $path . '*/', GLOB_ONLYDIR );

		foreach ( $subdirectories as $subdirectory ) {
			$this->parse( $subdirectory );
		}
	}

	/**
	 * @return void
	 */
	private function collect(): void
	{
		// TODO: Add grouping logic.
	}

	/**
	 * Get builder and build
	 * @return void
	 */
	private function build(): void
	{
		$builder = $this->getBuilder();
		$builder->build();
	}

	/**
	 * Get the Builder
	 * @return Builder|MarkdownBuilder|HtmlBuilder
	 */
	private function getBuilder(): Builder|MarkdownBuilder|HtmlBuilder
	{
		if ( 'markdown' === $this->config['format'] ) {
			return new MarkdownBuilder( $this->tree, $this->objects, $this->config );
		}

		if ( 'html' === $this->config['format'] ) {
			return new HtmlBuilder( $this->tree, $this->objects, $this->config );
		}

		return new Builder( $this->tree, $this->objects, $this->config );
	}

	/**
	 * Wrap up.
	 * @return void
	 */
	private function finish(): void
	{
		$this->script::writeInfo( 'Documentation generated successfully.' );
	}
}
