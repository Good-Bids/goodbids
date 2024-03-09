<?php
/**
 * API Template
 *
 * phpcs:disable
 *
 * @package WPDocsGenerator
 */

use Viget\ComposerScripts\WPDocsGenerator\DocItem;

/**
 * @param DocItem[] $items
 * @param string $current
 * @return void
 */
function generateApi( array $items, string $current = '' ): void {
	foreach ($items as $item) {
		if ('public' !== $item->access) {
			continue;
		}

		$name = $item->name;
		$separator = $item->isStatic ? '::' : '->';

		if (in_array($item->node, ['function', 'method'], true)) {
			$name .= '()';
		} elseif ('property' === $item->node && $item->isStatic) {
			$name = '$' . $name;
		}

		$fullName = $current ? $current . $separator . $name : $name;

		if (!in_array($item->node, ['property', 'method'], true) || empty($item->api)) {
			// Display the API information
			echo $fullName . PHP_EOL;
		}

		// Recursively call the function for sub-APIs
		if (!empty($item->api)) {
			generateApi($item->api, $fullName);
		}
	}
}

generateApi( $this->collection->tree );
