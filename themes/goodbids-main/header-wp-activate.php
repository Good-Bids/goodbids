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
	<div id="activate-header">
		<h1 class="text-lg header-title">Thanks for Joining <?php echo esc_html( get_option( 'blogname' ) ); ?> on GOODBIDS!</h1>
		<h2 class="text-md">What's next?</h2>
		<ol>
			<li>Review the Nonprofit <?php echo wp_kses_post( goodbids()->sites->get_terms_conditions_link() ); ?></li>
			<li>
				Log in to GOODBIDS with your username and temporary password shown below.</br>
				<small>Note: This information was also sent to you via email..</small>
				<p><a href="">Login</a></p>
			</li>
			<li>
				Enable Two-Factor authentication and set a new password for your account.</br>
				<small>Note: Two-Factor authentication is required.</small>
			</li>
		</ol>
	</div>
	<hr/>
	<div id="activate-content">
