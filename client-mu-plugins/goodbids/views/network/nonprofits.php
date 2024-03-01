<?php
/**
 * Network Admin Nonprofits Page
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Network\NonprofitsTable;

$nonprofits_table = new NonprofitsTable();
$nonprofits_table->prepare_items();
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Nonprofits', 'goodbids' ); ?></h2>

	<?php $nonprofits_table->display(); ?>

</div>
