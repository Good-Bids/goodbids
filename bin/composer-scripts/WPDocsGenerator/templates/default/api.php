<?php
/**
 * API Template
 *
 * phpcs:disable
 *
 * @package WPDocsGenerator
 */

use Viget\ComposerScripts\WPDocsGenerator\DocItem;

$objects = $this->collection->objects;

/**
 * @param DocItem[] $tree
 * @param string $current
 * @return void
 */
function generateApi( array $items, string $current = '' ): void {
	foreach ( $items as $item ) {
		if ( 'public' !== $item->access ) {
			continue;
		}

		if ( ! empty( $item->api ) ) {
			$current = $item->getReference( true );
			echo 'API: ' . $current . PHP_EOL;

			generateApi( $item->api, $current );
			$current = '';

			echo PHP_EOL;
			continue;
		}

		if ( ! $current ) {
			continue;
		}

		// Don't add static methods to the API.
		if ( str_ends_with( $current, '()' ) && $item->isStatic ) {
			continue;
		}

		$name      = $item->name;
		$separator = $item->isStatic ? '::' : '->';

		if ( in_array( $item->node, [ 'function', 'method' ], true ) ) {
			$name .= '()';
		} elseif ( 'property' === $item->node && $item->isStatic ) {
			$name = '$' . $name;
		}

		echo $current . $separator . $name . PHP_EOL;
	}
}

generateApi( $this->collection->tree );
