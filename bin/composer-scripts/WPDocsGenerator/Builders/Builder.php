<?php
/**
 * Documentation Builder
 *
 * @package WPDocsGenerator
 */

namespace Viget\ComposerScripts\WPDocsGenerator\Builders;

use Viget\ComposerScripts\WPDocsGenerator\DocItem;

class Builder
{

	/**
	 * @var DocItem[]
	 */
	private array $objects;

	/**
	 * @var array
	 */
	private array $config;

	/**
	 * @param DocItem[] $objects
	 * @param array $config
	 */
	public function __construct( array $objects, array $config )
	{
		$this->objects = $objects;
		$this->config = $config;
	}

	/**
	 * Build the documentation
	 * @return void
	 */
	public function build(): void
	{
		$outputDir = $this->config['output'];
		$this->emptyDirectory($outputDir);

		foreach ( $this->objects as $object ) {
			$this->buildObject( $object );
		}
	}

	/**
	 * Build the object
	 * @param DocItem $object
	 * @return void
	 */
	public function buildObject( DocItem $object ): void
	{
		$path = $this->getOutputPath( $object );
		$file = $this->getObjectFile( $object );

		$filename = $path . '/' . $file;
		$contents = $this->buildObjectContents( $object );
		$this->writeToFile( $filename, $contents );
	}

	/**
	 * Get the output path.
	 * @param DocItem $object
	 * @return string
	 */
	public function getOutputPath( DocItem $object ): string
	{
		return $this->config['output'];
	}

	/**
	 * Get the filename for the given object.
	 * @param DocItem $object
	 * @return string
	 */
	public function getObjectFile( DocItem $object ): string
	{
		return 'index.txt';
	}

	/**
	 * Build the contents of the object
	 * @param DocItem $object
	 * @return string
	 */
	public function buildObjectContents( DocItem $object ): string
	{
		$template = $this->getTemplate( $object );

		ob_start();
		include $template;
		return ob_get_clean();
	}

	/**
	 * Get the template path for the object
	 * @param DocItem $object
	 * @return string
	 */
	public function getTemplate( DocItem $object ): string
	{
		$template  = 'default.php';
		$directory = sprintf(
			'%s/templates/%s/',
			$this->config['_basedir'],
			$this->config['format']
		);

		$templates = [
			'PhpParser\Node\Stmt\Function_'   => 'function.php',
			'PhpParser\Node\Stmt\Class_'      => 'class.php',
			'PhpParser\Node\Stmt\ClassConst'  => 'class-const.php',
			'PhpParser\Node\Stmt\ClassMethod' => 'class-method.php',
		];

		if ( array_key_exists( $object->type, $templates ) ) {
			if ( file_exists( $directory . $templates[ $object->type ] ) ) {
				$template = $templates[ $object->type ];
			}
		}

		return $directory . $template;
	}

	/**
	 * Write or append contents to a file
	 *
	 * @param string $path
	 * @param string $contents
	 * @return void
	 */
	public function writeToFile(string $path, string $contents): void
	{
		// Ensure the directory exists
		$directory = pathinfo($path, PATHINFO_DIRNAME);
		if (!is_dir($directory)) {
			mkdir($directory, 0755, true);
		}

		// Open the file in append mode or create it if it doesn't exist
		$file = fopen($path, 'a+');

		// Write the contents to the file
		fwrite($file, $contents);

		// Close the file
		fclose($file);
	}

	/**
	 * Empty the output directory
	 * @param string $directoryPath
	 * @return void
	 */
	function emptyDirectory( string $directoryPath ): void
	{
		// Check if the directory exists
		if (!is_dir($directoryPath)) {
			return;
		}

		// Open the directory
		$directory = opendir($directoryPath);

		// Iterate through each item in the directory
		while (($item = readdir($directory)) !== false) {
			// Skip current and parent directory entries
			if ($item != '.' && $item != '..') {
				$itemPath = $directoryPath . '/' . $item;

				// Remove files and subdirectories
				if (is_dir($itemPath)) {
					// Recursively empty subdirectories
					$this->emptyDirectory($itemPath);
					// Remove the empty directory
					rmdir($itemPath);
				} else {
					// Remove the file
					unlink($itemPath);
				}
			}
		}

		// Close the directory handle
		closedir($directory);
	}

	/**
	 * Pretty print a variable
	 *
	 * @param mixed $var
	 * @param int $offset
	 * @return void
	 */
	public function prettyPrint( mixed $var, int $offset = 0 ): void
	{
		$string = json_encode( $var, JSON_PRETTY_PRINT ); // phpcs:ignore

		if ( ! $offset ) {
			echo $string; // phpcs:ignore
			return;
		}

		$lines = explode( PHP_EOL, $string );

		foreach ( $lines as $no => $line ) {
			$line = str_replace( '    ', "\t", $line );
			$line = str_replace( '\\\\', '\\', $line );

			if ( $no === 0 ) {
				echo $line . PHP_EOL; // phpcs:ignore
				continue;
			}

			echo str_repeat( "\t", $offset ) . $line . PHP_EOL; // phpcs:ignore
		}
	}

}
