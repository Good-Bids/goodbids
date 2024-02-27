<?php
/**
 * Edit Site Form Fields
 *
 * @global bool $verified
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<h3><?php esc_html_e( 'GoodBids Nonprofit Verification', 'goodbids' ); ?></h3>

<?php if ( $verified ) : ?>
	<p>
		<span class="dashicons dashicons-yes-alt"></span>
		<?php esc_html_e( 'This site has been verified.', 'goodbids' ); ?>
	</p>
<?php else : ?>
	<p>
		<span class="dashicons dashicons-no-alt"></span>
		<?php esc_html_e( 'This site is still pending verification.', 'goodbids' ); ?>
	</p>
<?php endif; ?>
