<?php
/**
 * Function Object Template
 *
 * phpcs:disable
 *
 * @var DocItem $object
 *
 * @package WPDocsGenerator
 */

use Viget\ComposerScripts\WPDocsGenerator\DocItem;

?>
<?php echo $object->getReference(); ?>()
	description: <?php echo $object->description; ?>

	source: <?php echo $object->path . ':' . $object->lineNumber; ?>

<?php if ( $object->namespace ) : ?>
	namespace: <?php echo $object->namespace; ?>

<?php endif; ?>
	parameters:
		<?php $this->prettyPrint( $object->parameters, 2 ); ?>
	returns:
		<?php $this->prettyPrint( $object->returnTypes, 2 ); ?>

