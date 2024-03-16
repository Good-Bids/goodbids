<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * @global WC_Order $order
 * @global int $order_id
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

use GoodBids\Auctions\Bids;
use GoodBids\Auctions\Rewards;
use GoodBids\Frontend\Request;
use GoodBids\Frontend\SupportRequest;
use GoodBids\Network\Nonprofit;

defined( 'ABSPATH' ) || exit;

$notes      = $order->get_customer_order_notes();
$nonprofit  = new Nonprofit( get_current_blog_id() );
$order_type = goodbids()->woocommerce->orders->get_type( $order_id );
?>
<p>
	<?php if ( goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) : ?>
		<?php
			printf(
				/* translators: 1: order number 2: blog title 3: order date 4: order status */
				esc_html__( 'Your donation #%1$s to %2$s was placed on %3$s and is currently %4$s.', 'woocommerce' ),
				'<mark class="order-number">' . wp_kses_post( $order->get_order_number() ) . '</mark>',
				'<mark class="order-site-title">' . wp_kses_post( get_bloginfo( 'title' ) ) . '</mark>',
				'<mark class="order-date">' . wp_kses_post( wc_format_datetime( $order->get_date_created() ) ) . '</mark>',
				'<mark class="order-status">' . wp_kses_post( wc_get_order_status_name( $order->get_status() ) ) . '</mark>'
			);
		?>
	<?php elseif ( goodbids()->woocommerce->orders->is_reward_order( $order_id ) ) : ?>
		<?php
			printf(
				/* translators: 1: blog title 2: order date 3: order status 4: order number */
				esc_html__( 'Your reward claim with %1$s was placed on %2$s and is currently %3$s. Your order number is #%4$s', 'woocommerce' ),
				'<mark class="order-site-title">' . wp_kses_post( get_bloginfo( 'title' ) ) . '</mark>',
				'<mark class="order-date">' . wp_kses_post( wc_format_datetime( $order->get_date_created() ) ) . '</mark>',
				'<mark class="order-status">' . wp_kses_post( wc_get_order_status_name( $order->get_status() ) ) . '</mark>',
				'<mark class="order-number">' . wp_kses_post( $order->get_order_number() ) . '</mark>'
			);
		?>
	<?php else : ?>
		<?php
			printf(
			/* translators: 1: order number 2: order date 3: order status */
				esc_html__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'woocommerce' ),
				'<mark class="order-number">' . wp_kses_post( $order->get_order_number() ) . '</mark>',
				'<mark class="order-date">' . wp_kses_post( wc_format_datetime( $order->get_date_created() ) ) . '</mark>',
				'<mark class="order-status">' . wp_kses_post( wc_get_order_status_name( $order->get_status() ) ) . '</mark>'
			);
		?>
	<?php endif; ?>
</p>

<?php if ( $notes ) : ?>
	<h2><?php esc_html_e( 'Order updates', 'woocommerce' ); ?></h2>
	<ol class="woocommerce-OrderUpdates commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="woocommerce-OrderUpdate comment note">
			<div class="woocommerce-OrderUpdate-inner comment_container">
				<div class="woocommerce-OrderUpdate-text comment-text">
					<p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<div class="woocommerce-OrderUpdate-description description">
						<?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>

<?php do_action( 'woocommerce_view_order', $order_id ); ?>

<?php
if ( ! $order_type ) {
	return;
}

if ( Bids::ITEM_TYPE === $order_type ) {
	$request_type = Request::TYPE_BID;
} elseif ( Rewards::ITEM_TYPE === $order_type ) {
	$request_type = Request::TYPE_REWARD;
} else {
	return;
}

$request_args = [
	'type'    => $request_type,
	'auction' => $nonprofit->get_id() . '|' . goodbids()->woocommerce->orders->get_auction_id( $order_id ),
	'bid'     => $nonprofit->get_id() . '|' . $order_id,
];
?>
<h3><?php esc_html_e( 'Need Help?', 'goodbids' ); ?></h3>

<p><a href="<?php echo esc_url( goodbids()->support->get_form_url( $request_args ) ); ?>"><?php esc_html_e( 'Submit a support request', 'goodbids' ); ?></a> <?php esc_html_e( 'to', 'goodbids' ); ?> <?php echo esc_html( $nonprofit->get_name() ); ?> <?php esc_html_e( 'for this bid.', 'goodbids' ); ?></p>
