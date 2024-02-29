<?php
/**
 * Network Admin Auctions Page
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Network\AuctionsTable;

$auctions_table = new AuctionsTable();
$auctions_table->prepare_items();
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Auctions', 'goodbids' ); ?></h2>

	<?php $auctions_table->display(); ?>

</div>
