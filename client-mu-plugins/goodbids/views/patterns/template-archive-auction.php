<?php
/**
 * Pattern: Auction Archive
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>

<!-- wp:group {"tagName":"main","style":{"spacing":{"blockGap":"0","margin":{"top":"0"}}},"layout":{"type":"constrained","justifyContent":"center"}} -->
<main class="wp-block-group" style="margin-top:0">

	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
	<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--50)">
		<!-- wp:heading {"level":1,"align":"wide","style":{"typography":{"textTransform":"capitalize"}}} -->
		<h1 class="wp-block-heading alignwide" style="text-transform:capitalize">
			<?php esc_html_e( 'Our Auctions', 'goodbids' ); ?>
		</h1>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--30)">
		<!-- wp:acf/all-auctions {"name":"acf/all-auctions","align":"wide","mode":"preview"} /-->
	</div>
	<!-- /wp:group -->
</main>
<!-- /wp:group -->
