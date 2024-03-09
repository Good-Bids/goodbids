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
	 * @var ?CodeCollection
	 */
	private ?CodeCollection $collection = null;

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

		// Group objects together, starting at the root of the tree.
		$this->collect( $this->collection->tree );

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
				$collection = $this->parser->parse( $file, $relative, $this->collection );

				if ( is_null( $collection ) ) {
					$this->script::writeError( 'Parser Collection Error: ' . $this->parser->error );
					continue;
				}

				$this->collection = $collection;
			}
		}

		$subdirectories = glob( $path . '*/', GLOB_ONLYDIR );

		foreach ( $subdirectories as $subdirectory ) {
			$this->parse( $subdirectory );
		}
	}

	/**
	 * @param DocItem[] $objects
	 * @return void
	 */
	private function collect( array &$objects ): void
	{
		foreach ( $objects as &$object ) {
			$object = $this->collectReturnTypes( $object );

			// Traverse through this objects API.
			$this->collect( $object->api );
		}
	}

	private function collectReturnTypes( DocItem $object ): DocItem
	{
		foreach ( $object->returnTypes as $returnType ) {
			if ( ! array_key_exists( $returnType, $this->collection->objects ) ) {
				continue;
			}

			$returnObject = $this->collection->objects[ $returnType ];

			// Exclude circular references.
			if ( 'class' !== $returnObject->node || $returnObject->getReference() === $object->getReference() ) {
				continue;
			}

			$object->api = $this->collectPropertiesAndMethods( $returnObject );
		}

		return $object;
	}

	/**
	 * @param DocItem $object
	 * @param int $depth
	 * @return array
	 */
	private function collectPropertiesAndMethods( DocItem $object ): array
	{
		$api = [];

		foreach ( $object->methods as $method ) {
			if ( 'public' !== $method->access || in_array( $method->name, [ '__construct', 'get_instance' ], true ) ) {
				continue;
			}

			$api[$method->getReference()] = $this->collectReturnTypes($method);
		}

		foreach ( $object->properties as $property ) {
			if ( 'public' !== $property->access || 'instance' === $property->name ) {
				continue;
			}

			$api[$property->getReference()] = $this->collectReturnTypes($property);
		}

		return $api;
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
			return new MarkdownBuilder( $this->collection, $this->config );
		}

		if ( 'html' === $this->config['format'] ) {
			return new HtmlBuilder( $this->collection, $this->config );
		}

		return new Builder( $this->collection, $this->config );
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
