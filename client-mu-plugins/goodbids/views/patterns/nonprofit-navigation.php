<?php
/**
 * Template part for displaying the navigation
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GoodBids
 */
$nav_items = goodbids()->sites->get_nonprofit_navigation();
?>

<!-- wp:navigation -->
	<?php foreach ( $nav_items as $nav_item ) : ?>
		<!-- wp:navigation-link {"label":"<?php echo esc_html( $nav_item['label'] ); ?>","type":"page","id":<?php echo esc_html( $nav_item['ID'] ); ?>,"url":"<?php echo esc_url( $nav_item['url'] ); ?>","kind":"post-type"} /-->
	<?php endforeach; ?>
<!-- /wp:navigation -->
<!-- wp:woocommerce/customer-account {"displayStyle":"text_only","iconStyle":"alt","iconClass":"wc-block-customer-account__account-icon","className":"btn","textColor":"contrast","fontSize":"x-small","style":{"layout":{"selfStretch":"fit","flexSize":null},"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}}} /-->
