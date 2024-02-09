<?php
/**
 * Network Admin Invoices
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Network\InvoicesTable;

$invoices_table = new InvoicesTable();
$invoices_table->prepare_items();
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Invoices', 'goodbids' ); ?></h2>

	<?php $invoices_table->display(); ?>

</div>
