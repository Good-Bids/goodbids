<?php
/**
 * Network Admin Bidders Page
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Network\BiddersTable;

$bidders_table = new BiddersTable();
$bidders_table->prepare_items();
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Bidders', 'goodbids' ); ?></h2>

	<?php $bidders_table->display(); ?>

</div>
