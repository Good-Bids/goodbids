<?php
/**
 * Links for displaying the navigation
 *
 * @var array $nav_links
 *
 * @since 1.0.0
 * @package GoodBids
 */
?>

<?php foreach ( $nav_links as $nav_link ) : ?>
	<!-- wp:navigation-link {"label":"<?php echo esc_attr( $nav_link->post_title ); ?>","type":"<?php echo esc_attr( $nav_link->post_type ); ?>","id":<?php echo esc_attr( $nav_link->ID ); ?>,"url":"/<?php echo esc_attr( $nav_link->post_name ); ?>","kind":"post-type"} /-->
<?php endforeach; ?>
