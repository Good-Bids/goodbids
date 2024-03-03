<?php
/**
 * Default Object Template
 *
 * phpcs:disable
 *
 * @var DocItem $object
 *
 * @package WPDocsGenerator
 */

use Viget\ComposerScripts\WPDocsGenerator\DocItem;

?>
<?php echo $object->getReference(); ?>

	type: <?php echo $object->type; ?>

	description: <?php echo $object->description; ?>

	source: <?php echo $object->path . ':' . $object->lineNumber; ?>

	namespace: <?php echo $object->namespace; ?>

	object:
		<?php $this->prettyPrint( $object, 2 ); ?>

