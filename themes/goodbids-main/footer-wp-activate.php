<?php defined( 'ABSPATH' ) || exit; ?>

	</div>
	<hr/>
	<p>
		<?php echo $post ? esc_html( $post->post_title ) . ' | ' : ''; ?><?php echo esc_html( get_option( 'blogname' ) ); ?>
	</p>
</body>
</html>
