<?php
/**
 * PHP File Parser
 *
 * @package WPDocsGenerator
 */

namespace Viget\ComposerScripts\WPDocsGenerator;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

/**
 * Parser Class
 */
class Parser {

	/**
	 * @var \PhpParser\Parser
	 */
	private \PhpParser\Parser $parser;

	/**
	 * @var string
	 */
	public string $error = '';

	/**
	 * Parser constructor.
	 */
	public function __construct() {
		$this->parser = ( new ParserFactory() )->createForNewestSupportedVersion();
	}

	/**
	 * @param string $path
	 * @param string $relative_path
	 * @param ?CodeCollection $collection
	 * @return ?CodeCollection
	 */
	public function parse( string $path, string $relative_path, ?CodeCollection $collection ): ?CodeCollection
	{
		if ( ! file_exists( $path ) ) {
			throw new \InvalidArgumentException( 'File does not exist' );
		}

		$source = file_get_contents( $path ); // phpcs:ignore

		try {
			$parsed = $this->parser->parse( $source );
		} catch ( \Error $e ) {
			$this->error = $e->getMessage();
			return null;
		}

		$traverser = new NodeTraverser();

		if ( ! is_null( $collection ) ) {
			$collection->setPath( $relative_path );
		} else {
			$collection = new CodeCollection( $relative_path );
		}

		$traverser->addVisitor( $collection );
		$traverser->traverse( $parsed );

		return $collection;
	}

	/**
	 * @param string $path
	 * @param string $relative_path
	 * @param ?ApiCollection $collection
	 * @return ?ApiCollection
	 */
	public function parseApi( string $path, string $relative_path, ?ApiCollection $collection ): ?ApiCollection
	{
		if ( ! file_exists( $path ) ) {
			throw new \InvalidArgumentException( 'File does not exist' );
		}

		$source = file_get_contents( $path ); // phpcs:ignore

		try {
			$parsed = $this->parser->parse( $source );
		} catch ( \Error $e ) {
			$this->error = $e->getMessage();
			return null;
		}

		$traverser = new NodeTraverser();

		if ( ! is_null( $collection ) ) {
			$collection->setPath( $relative_path );
		} else {
			$collection = new ApiCollection( $relative_path );
		}

		$traverser->addVisitor( $collection );
		$traverser->traverse( $parsed );

		return $collection;
	}

	/**
	 * @param string $path
	 * @param string $relative_path
	 * @param ?MultiplesCollection $collection
	 * @return ?MultiplesCollection
	 */
	public function parseMultiples( string $path, string $relative_path, ?MultiplesCollection $collection ): ?MultiplesCollection
	{
		if ( ! file_exists( $path ) ) {
			throw new \InvalidArgumentException( 'File does not exist' );
		}

		$source = file_get_contents( $path ); // phpcs:ignore

		try {
			$parsed = $this->parser->parse( $source );
		} catch ( \Error $e ) {
			$this->error = $e->getMessage();
			return null;
		}

		$traverser = new NodeTraverser();

		if ( ! is_null( $collection ) ) {
			$collection->setPath( $relative_path );
		} else {
			$collection = new MultiplesCollection( $relative_path );
		}

		$traverser->addVisitor( $collection );
		$traverser->traverse( $parsed );

		return $collection;
	}
}
