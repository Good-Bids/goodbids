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
	<div id="activate-page">
