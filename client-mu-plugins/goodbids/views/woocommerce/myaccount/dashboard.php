<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

printf(
	'<h1 class="mt-0">%s</h1>',
	esc_html__( 'Dashboard', 'goodbids' )
);

do_action( 'woocommerce_account_dashboard' );

/* Header */
goodbids()->load_view( 'woocommerce/myaccount/dashboard-header.php' );

/* Auctions table */
$auctions = goodbids()->sites->get_user_participating_auctions( null, 3 );

if ( count( $auctions ) ) :
	printf( '<h2 class="mt-10 font-normal text-md>%s</h2>', esc_html__( 'Latest Activity', 'goodbids' ) );
	goodbids()->load_view( 'woocommerce/myaccount/auctions-table.php', compact( 'auctions' ) );
endif;

/* Auctions grid */
goodbids()->load_view( 'woocommerce/myaccount/dashboard-auctions.php' );
