<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * @var WP_User $current_user
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<h1 class="mt-0"><?php esc_html_e( 'Dashboard', 'goodbids' ); ?></h1>

<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_account_dashboard' );

/* Header */
goodbids()->load_view( 'woocommerce/myaccount/dashboard-header.php' );

?>

<?php
/*Auctions table */
$auctions = goodbids()->sites->get_user_participating_auctions( null, 3 );

if ( count( $auctions ) ) {
	printf( '<h2 class="mt-10 font-normal text-md>%s</h2>', esc_html__( 'Latest Activity', 'goodbids' ) );
	goodbids()->load_view( 'woocommerce/myaccount/auctions-table.php', compact( 'auctions' ) );
}


/* Auctions grid */
goodbids()->load_view( 'woocommerce/myaccount/dashboard-auctions.php' );


/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action( 'woocommerce_before_my_account' );

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */