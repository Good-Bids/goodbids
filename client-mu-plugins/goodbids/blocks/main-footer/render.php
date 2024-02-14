<?php
/**
 * Block: Main Footer
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$main_footer  = new GoodBids\Blocks\MainFooter( $block );
$terms_link   = goodbids()->sites->get_terms_conditions_link();
$privacy_link = goodbids()->sites->get_privacy_policy_link();
?>
<section <?php block_attr( $block, 'wp-block-group alignwide has-background has-global-padding is-layout-constrained wp-block-group-is-layout-constrained' ); ?>>
	<div class="wp-block-group alignwide has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
		<div class="wp-block-group alignwide has-small-font-size is-content-justification-space-between is-nowrap is-layout-flex wp-container-core-group-layout-30 wp-block-group-is-layout-flex" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40);justify-content: space-between;flex-wrap: wrap;">

			<p><?php echo wp_kses_post( $main_footer->get_sitename_text() ); ?></p>

			<div class="wp-block-group is-nowrap is-layout-flex wp-container-core-group-layout-29 wp-block-group-is-layout-flex">

				<?php if ( $terms_link ) : ?>
					<p>
						<?php echo wp_kses_post( $terms_link ); ?>
					</p>
				<?php endif; ?>

				<?php if ( $privacy_link ) : ?>
					<p>
						<?php echo wp_kses_post( $privacy_link ); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
