<?php
/**
 * Block: Support Request Form
 *
 * @var array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Blocks\SupportRequestForm;

$support_form = new SupportRequestForm( $block );
?>
<section <?php block_attr( $block ); ?>>
	<?php $support_form->render(); ?>
</section>
