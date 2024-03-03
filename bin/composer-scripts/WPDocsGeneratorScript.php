<?php
/**
 * Generate Documentation
 *
 * @package WPDocsGenerator
 */

namespace Viget\ComposerScripts;

use Composer\Script\Event;
use Viget\ComposerScripts\WPDocsGenerator\WPDocsGenerator;

/**
 * WPDocsGeneratorScript
 */
class WPDocsGeneratorScript extends ComposerScript {

	/**
	 * @var ?WPDocsGeneratorScript
	 */
	public static ?WPDocsGeneratorScript $instance = null;

	/**
	 * @var array $config
	 */
	private static array $config = [];

	/**
	 * Generate Documentation
	 *
	 * @param Event $event
	 */
	public static function make( Event $event ): void
	{
		if ( null === self::$instance ) {
			self::$instance = new WPDocsGeneratorScript();
		}

		self::$instance::setEvent( $event );

		if ( self::needsSetup() ) {
			self::setup();
		}

		$defaults = [
			'source' => null,
			'ignore' => [ 'vendor', 'node_modules', 'build', 'dist' ],
		];

		$generator = WPDocsGenerator::getInstance();
		$generator->init( self::$instance, array_merge( $defaults, self::$config ) );
		$generator->generate();
	}

	/**
	 * Check if setup has been run.
	 * @return bool
	 */
	public static function needsSetup(): bool
	{
		return empty( self::$config );
	}

	/**
	 * Run the setup process
	 * @return void
	 */
	public static function setup(): void
	{
		self::$config['source'] = self::getSource();
	}

	/**
	 * Get the source directory
	 * @return string
	 */
	public static function getSource(): string
	{
		$default = ! empty( self::$config['source'] ) ? self::$config['source'] : './';
		$source  = self::ask( 'What is the source directory?', $default );
		$source  = self::translatePath( $source );

		if ( ! is_dir( $source ) ) {
			self::writeError( sprintf( 'The source directory does not exist. (%s)', $source ) );
			return self::getSource();
		}

		if ( ! str_ends_with( '/', $source ) ) {
			$source .= '/';
		}

		self::writeComment( 'Using: ' . $source );

		return $source;
	}
}
