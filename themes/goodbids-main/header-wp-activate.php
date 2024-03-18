<?php defined( 'ABSPATH' ) || exit;

global $post;

?>
<!doctype html>
<html lang="en">
	<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="Cache-control" content="public">
	<?php wp_head(); ?>
	<title>
		<?php echo $post ? esc_html( $post->post_title ) . ' | ' : ''; ?><?php echo esc_html( get_option( 'blogname' ) ); ?>
	</title>
</head>
<body>
	<div class="activate-header">
		<?php if ( get_custom_logo( get_main_site_id() ) ) : ?>
			<figure>
				<?php echo wp_kses_post( get_custom_logo( get_main_site_id() ) ); ?>
			</figure>
		<?php endif; ?>
		<h1 class="text-lg header-title">
		<?php
			printf(
				/* translators: %s: site name */
				__( 'Thanks for Joining %s on GOODBIDS!', 'goodbids' ),
				esc_html( get_option( 'blogname' ) )
			)
			?>
		</h1>
		<h2 class="text-md"><?php esc_html_e( 'What\'s next?', 'goodbids' ); ?></h2>
		<ol>
			<li>
				<?php
				printf(
					__( 'Review the Nonprofit %s.', 'goodbids' ),
					wp_kses_post( goodbids()->sites->get_terms_conditions_link() )
				);
				?>
			</li>
			<li>
				<?php esc_html_e( 'Log in to GOODBIDS with your username and temporary password shown below.', 'goodbids' ); ?></br>
				<small><?php esc_html_e( 'Note: This information was also sent to you via email.', 'goodbids' ); ?></small>
				<p><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Login', 'goodbids' ); ?></a></p>
			</li>
			<li>
				<?php esc_html_e( 'Enable Two-Factor authentication and set a new password for your account.', 'goodbids' ); ?></br>
				<small><?php esc_html_e( 'Note: Two-Factor authentication is required.', 'goodbids' ); ?></small>
			</li>
		</ol>
	</div>
	<hr/>
	<div class="activate-content">
