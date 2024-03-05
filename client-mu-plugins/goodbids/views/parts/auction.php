<?php
/**
 * Part: Auction block template
 *
 * Used inside of auction loop.
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Utilities\Log;

$auction      = goodbids()->auctions->get();
$url          = is_admin() ? '#' : $auction->get_url();
$time_class   = '';
$time         = '';
$clock_svg    = false;
$time_zone    = wp_timezone();
$current_date = current_datetime();
$site_logo    = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) );

if ( $auction->has_started() ) {
	try {
		$end_date = new DateTime( $auction->get_end_date_time(), $time_zone );
	} catch ( Exception $e ) {
		Log::error( $e->getMessage() );
		$end_date = $auction->get_end_date_time();
	}
	$remaining_time = $current_date->diff( $end_date );

	$time = __( 'Ending ', 'goodbids' ) . $auction->get_end_date_time( 'M d' );

	if ( $remaining_time->d < 1 ) {
		$clock_svg = true;
		$time      = $remaining_time->format( '%hh %im' );
	} elseif ( $remaining_time->d < 1 && $remaining_time->h < 1 ) {
		$time_class .= 'text-red-500';
		$clock_svg   = true;
		$time        = $remaining_time->format( '%im' );
	}
} else {
	try {
		$start_date = new DateTime( $auction->get_start_date_time(), $time_zone );
	} catch ( Exception $e ) {
		Log::error( $e->getMessage() );
		$start_date = $auction->get_start_date_time();
	}
	$remaining_time = $current_date->diff( $start_date );

	$time = __( 'Coming ', 'goodbids' ) . $auction->get_start_date_time( 'M d' );

	if ( $remaining_time->h < 1 ) {
		$clock_svg = true;
		$time      = __( 'Coming in ', 'goodbids' ) . $remaining_time->format( '%im' );
	} elseif ( $remaining_time->d < 1 ) {
		$clock_svg = true;
		$time      = __( 'Coming in ', 'goodbids' ) . $remaining_time->format( '%hh %im' );
	}
}
?>
<li class="block overflow-hidden">
	<a href="<?php echo esc_url( $url ); ?>" class="no-underline hover:underline hover:text-black">
		<figure class="m-0 overflow-hidden rounded-sm wp-block-post-featured-image aspect-square">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'woocommerce_thumbnail', [ 'class' => 'w-full h-full object-cover' ] ); ?>
			<?php else : ?>
				<?php echo wp_kses_post( goodbids()->rewards->get_product( $auction->get_id() )->get_image( 'woocommerce_thumbnail', [ 'class' => 'w-full h-full object-cover' ] ) ); ?>
			<?php endif; ?>
		</figure>
	</a>

	<a href="<?php echo esc_url( get_site_url() ); ?>" class="flex items-center gap-4 py-4 no-underline border-t-0 border-b border-solid border-b-contrast-2 border-x-0 hover:underline">
		<?php if ( $site_logo ) : ?>
			<div class="w-10 h-10 overflow-hidden rounded-full shrink-0">
				<img class="object-contain w-10 h-10" src="<?php echo esc_url( $site_logo[0] ); ?>" width="<?php echo esc_attr( $site_logo[1] ); ?>" height="<?php echo esc_attr( $site_logo[2] ); ?>" />
			</div>
		<?php endif; ?>
		<p class="text-sm font-bold line-clamp-1 my-1.5 mx-0">
			<?php echo esc_html( get_bloginfo( 'title' ) ); ?>
		</p>
	</a>

	<a href="<?php echo esc_url( $url ); ?>" class="no-underline hover:underline">
		<?php if ( get_the_title() ) : ?>
			<h2 class="mt-4 mb-0 normal-case wp-block-post-title has-large-font-size line-clamp-3">
				<?php the_title(); ?>
			</h2>
		<?php endif; ?>
	</a>

	<div class="flex mt-4 text-xs font-bold gap-7">
		<div class="flex items-center gap-2 <?php echo esc_attr( $time_class ); ?>">
			<?php if ( $clock_svg ) : ?>
				<svg width="24" aria-hidden="true" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M12 2C17.523 2 22 6.478 22 12C22 17.522 17.523 22 12 22C6.477 22 2 17.522 2 12C2 6.478 6.477 2 12 2ZM12 3.667C7.405 3.667 3.667 7.405 3.667 12C3.667 16.595 7.405 20.333 12 20.333C16.595 20.333 20.333 16.595 20.333 12C20.333 7.405 16.595 3.667 12 3.667ZM11.25 6C11.6295 6 11.9435 6.28233 11.9931 6.64827L12 6.75V12H15.25C15.664 12 16 12.336 16 12.75C16 13.1295 15.7177 13.4435 15.3517 13.4931L15.25 13.5H11.25C10.8705 13.5 10.5565 13.2177 10.5069 12.8517L10.5 12.75V6.75C10.5 6.336 10.836 6 11.25 6Z" fill="currentColor"/>
				</svg>
			<?php endif; ?>
			<?php echo esc_html( $time ); ?>
		</div>

		<div class="flex items-center gap-2">
			<svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M11.9999 9.00462C14.209 9.00462 15.9999 10.7955 15.9999 13.0046C15.9999 15.2138 14.209 17.0046 11.9999 17.0046C9.79073 17.0046 7.99987 15.2138 7.99987 13.0046C7.99987 10.7955 9.79073 9.00462 11.9999 9.00462ZM11.9999 10.5046C10.6192 10.5046 9.49987 11.6239 9.49987 13.0046C9.49987 14.3853 10.6192 15.5046 11.9999 15.5046C13.3806 15.5046 14.4999 14.3853 14.4999 13.0046C14.4999 11.6239 13.3806 10.5046 11.9999 10.5046ZM11.9999 5.5C16.6134 5.5 20.596 8.65001 21.701 13.0644C21.8016 13.4662 21.5574 13.8735 21.1556 13.9741C20.7537 14.0746 20.3465 13.8305 20.2459 13.4286C19.307 9.67796 15.9212 7 11.9999 7C8.07681 7 4.68997 9.68026 3.75273 13.4332C3.65237 13.835 3.24523 14.0794 2.84336 13.9791C2.44149 13.8787 2.19707 13.4716 2.29743 13.0697C3.40052 8.65272 7.38436 5.5 11.9999 5.5Z" fill="currentColor"/>
			</svg>
			<?php echo esc_html( $auction->get_watch_count() ); ?>
		</div>
	</div>
</li>
