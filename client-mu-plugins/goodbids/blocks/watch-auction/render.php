<?php
/**
 * Block: Watch Auction
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

if ( ! is_user_logged_in() ) {
	return;
}

$watching      = goodbids()->watchers->is_watching();
$watchers      = goodbids()->watchers->get_watcher_count();
$watch_text    = __( 'Watch', 'goodbids' );
$unwatch_text  = __( 'Unwatch', 'goodbids' );
$button_text   = $watching ? $unwatch_text : $watch_text;
$watch_class   = 'btn-fill';
$unwatch_class = 'btn-fill-secondary';
$button_class  = $watching ? $watch_class : $unwatch_class;
$post_url      = admin_url( 'admin-ajax.php' );
?>
<div <?php block_attr( $block ); ?>>
	<button
		data-controller="watch-auction"
		data-action="watch-auction#toggle"
		data-watch-auction-state-value="<?php echo esc_attr( intval( $watching ) ); ?>"
		data-watch-auction-id-value="<?php echo esc_attr( goodbids()->auctions->get_auction_id() ); ?>"
		data-watch-auction-watch-text-value="<?php echo esc_attr( $watch_text ); ?>"
		data-watch-auction-unwatch-text-value="<?php echo esc_attr( $unwatch_text ); ?>"
		data-watch-auction-watch-class-value="<?php echo esc_attr( $watch_class ); ?>"
		data-watch-auction-unwatch-class-value="<?php echo esc_attr( $unwatch_class ); ?>"
		data-watch-auction-ajax-url-value="<?php echo esc_attr( $post_url ); ?>"
		class="inline-flex items-center gap-2 no-underline text-md <?php echo esc_attr( $button_class ); ?>"
	>
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path
				d="M12.0001 9.00462C14.2093 9.00462 16.0001 10.7955 16.0001 13.0046C16.0001 15.2138 14.2093 17.0046 12.0001 17.0046C9.79098 17.0046 8.00012 15.2138 8.00012 13.0046C8.00012 10.7955 9.79098 9.00462 12.0001 9.00462ZM12.0001 10.5046C10.6194 10.5046 9.50012 11.6239 9.50012 13.0046C9.50012 14.3853 10.6194 15.5046 12.0001 15.5046C13.3808 15.5046 14.5001 14.3853 14.5001 13.0046C14.5001 11.6239 13.3808 10.5046 12.0001 10.5046ZM12.0001 5.5C16.6136 5.5 20.5962 8.65001 21.7012 13.0644C21.8018 13.4662 21.5576 13.8735 21.1558 13.9741C20.754 14.0746 20.3467 13.8305 20.2461 13.4286C19.3072 9.67796 15.9215 7 12.0001 7C8.07705 7 4.69022 9.68026 3.75298 13.4332C3.65261 13.835 3.24547 14.0794 2.8436 13.9791C2.44173 13.8787 2.19731 13.4716 2.29767 13.0697C3.40076 8.65272 7.3846 5.5 12.0001 5.5Z"
				fill="currentColor" />
		</svg>
		<span data-watch-auction-target="text">
			<?php echo esc_html( $button_text ); ?>
		</span>
	</button>
</div>
