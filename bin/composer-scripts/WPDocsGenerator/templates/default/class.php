<?php
/**
 * Class Object Template
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
	constants:
		<?php $this->prettyPrint( $object->constants, 2 ); ?>
	properties:
		<?php $this->prettyPrint( $object->properties, 2 ); ?>

