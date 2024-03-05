<?php
/**
 * Documentation Builder
 *
 * @package WPDocsGenerator
 */

namespace Viget\ComposerScripts\WPDocsGenerator\Builders;

use Viget\ComposerScripts\WPDocsGenerator\CodeCollection;
use Viget\ComposerScripts\WPDocsGenerator\DocItem;

class Builder
{

	/**
	 * @var CodeCollection
	 */
	private CodeCollection $collection;

	/**
	 * @var array
	 */
	private array $config;

	/**
	 * @param CodeCollection $collection
	 * @param array $config
	 */
	public function __construct( CodeCollection $collection, array $config )
	{
		$this->collection = $collection;
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

		$path = $this->getOutputPath();
		$tree = 'tree.txt';

		$tree_path = $path . '/' . $tree;

		ob_start();
		var_dump( $this->collection->tree ); // phpcs:ignore
		$contents = ob_get_clean();

		$this->writeToFile( $tree_path, $contents );

		$objects      = 'objects.txt';
		$objects_path = $path . '/' . $objects;

		ob_start();
		var_dump( $this->collection->objects ); // phpcs:ignore
		$contents = ob_get_clean();

		$this->writeToFile( $objects_path, $contents );
	}

	/**
	 * Get the output path.
	 * @param ?DocItem $object
	 * @return string
	 */
	public function getOutputPath( ?DocItem $object = null ): string
	{
		return $this->config['output'];
	}

	/**
	 * Get the filename for the given object.
	 * @param ?DocItem $object
	 * @return string
	 */
	public function getObjectFile( ?DocItem $object = null ): string
	{
		return 'index.txt';
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

		$template = match ( $object->node ) {
			'constant' => 'constant.php',
			'class' => 'class.php',
			'class-constant' => 'class-constant.php',
			'method' => 'method.php',
			'function' => 'function.php',
			'parameter' => 'parameter.php',
			default => 'default.php',
		};

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
			mkdir($directory, 0755, true); // phpcs:ignore
		}

		// Open the file in append mode or create it if it doesn't exist
		$file = fopen($path, 'a+'); // phpcs:ignore

		// Write the contents to the file
		fwrite($file, $contents); // phpcs:ignore

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
					rmdir($itemPath); // phpcs:ignore
				} else {
					// Remove the file
					unlink($itemPath); // phpcs:ignore
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
	 * @return void
	 */
	public function prettyPrint( mixed $var ): void
	{
		echo json_encode( $var, JSON_PRETTY_PRINT ); // phpcs:ignore
	}
}
