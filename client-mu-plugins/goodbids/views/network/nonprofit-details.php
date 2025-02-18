<?php
/**
 * Site Verification/Details
 *
 * @global int    $site_id
 * @global string $site_name
 * @global string $page_slug
 * @global bool   $verified
 * @global array  $data
 * @global array  $fields
 * @global string $prefix
 * @global bool   $disabled
 *
 * @since 1.0.0
 * @package GoodBids
 */

/* translators: %s: Site title. */
$page_title = sprintf( __( 'Edit Site: %s' ), $site_name );
?>
<div class="wrap">

	<h1><?php echo esc_html( $page_title ); ?></h1>

	<p class="edit-site-actions"><a href="<?php echo esc_url( get_home_url( $site_id, '/' ) ); ?>"><?php _e( 'Visit' ); ?></a> | <a href="<?php echo esc_url( get_admin_url( $site_id ) ); ?>"><?php _e( 'Dashboard' ); ?></a></p>

	<?php
	network_edit_site_nav(
		array(
			'blog_id'  => $site_id,
			'selected' => $page_slug,
		)
	);
	?>

	<?php if ( $verified ): ?>
		<h2><?php esc_html_e( 'GOODBIDS Nonprofit Details', 'goodbids' ); ?></h2>
	<?php else: ?>
		<h2><?php esc_html_e( 'GOODBIDS Nonprofit Verification', 'goodbids' ); ?></h2>
	<?php endif; ?>

	<form method="post" action="">
		<?php wp_nonce_field( 'gb-site-custom-data', '_wpnonce_gb-site-custom-data' ); ?>

		<table class="form-table" role="presentation">
			<?php
			foreach ( $fields as $key => $field ) :
				goodbids()->admin->render_field( $key, $field, $prefix, $data );
			endforeach;
			?>
		</table>

		<?php
		if ( ! $disabled ) :
			submit_button( __( 'Update Details', 'goodbids' ) );
		endif;
		?>
	</form>
</div>
