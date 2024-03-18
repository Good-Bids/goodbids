<?php defined( 'ABSPATH' ) || exit; ?>

	</div>
	<hr/>
	<div class="activate-footer">
		<p>
			<?php
			if ( get_option( 'blogdescription' ) ) {
				$blogdescription = '| ' . get_option( 'blogdescription' );
			}

			printf(
				/* translators: %1$s: Site name, %2$s: Site description. */
				__( '%1$s %2$s', 'goodbids' ),
				esc_html( get_option( 'blogname' ) ),
				esc_html( $blogdescription )
			);
			?>
		</p>
	</div>
</body>
</html>
