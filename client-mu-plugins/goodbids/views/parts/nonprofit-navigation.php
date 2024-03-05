<?php
/**
 * Links for displaying the default navigation
 *
 * @var int[] $nav_links
 *
 * @since 1.0.0
 * @package GoodBids
 */

foreach ( $nav_links as $page_id ) :
	?>
	<!-- wp:navigation-link {"label":"<?php echo esc_attr( get_the_title( $page_id ) ); ?>","type":"<?php echo esc_attr( get_post_type( $page_id ) ); ?>","id":<?php echo esc_attr( $page_id ); ?>,"url":"/<?php echo esc_attr( get_post_field( 'post_name', $page_id, 'edit' ) ); ?>","kind":"post-type"} /-->
	<?php
endforeach;
