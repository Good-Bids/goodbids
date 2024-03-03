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
	 * @return ?CodeCollection[]
	 */
	public function parse( string $path, string $relative_path ): ?array
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

		$traverser  = new NodeTraverser();
		$collection = new CodeCollection( $relative_path );
		$traverser->addVisitor( $collection );

		$traverser->traverse( $parsed );

		return $collection->objects;
	}
}
