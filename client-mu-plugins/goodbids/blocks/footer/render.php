<?php
/**
 * Block: Main Footer
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>

<section <?php block_attr( $block, 'wp-block-group alignwide has-background has-global-padding is-layout-constrained wp-block-group-is-layout-constrained' ); ?>>
	<div class="wp-block-group alignwide has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
		<div class="wp-block-group alignwide has-small-font-size is-content-justification-space-between is-nowrap is-layout-flex wp-container-core-group-layout-30 wp-block-group-is-layout-flex" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40);justify-content: space-between;flex-wrap: wrap;">
			<?php
			printf(
				'<p>%s</p>',
				esc_html__( 'GOODBIDS Positive Auctions', 'goodbids' )
			);
			?>
			<div class="wp-block-group is-nowrap is-layout-flex wp-container-core-group-layout-29 wp-block-group-is-layout-flex">
				<?php
				printf(
					'<p><a href="%s">%s</a></p>',
					esc_html__( '#', 'goodbids' ),
					esc_html__( 'Terms & Conditions', 'goodbids' )
				);
				?>
				<?php
				printf(
					'<p><a href="%s">%s</a></p>',
					esc_html__( '#', 'goodbids' ),
					esc_html__( 'Privacy Policy', 'goodbids' )
				);
				?>
			</div>
		</div>
	</div>
</section>
