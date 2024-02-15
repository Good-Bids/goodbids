<?php
/**
 * Top Referrers
 *
 * @global array $results
 *
 * @since 1.0.0
 * @package GoodBids
 */

if ( empty( $results ) ) :
	?>
	<p class="goodbids-top-referrers-empty">
		<?php echo esc_html__( 'No Referrals yet. Be the first one!', 'goodbids' ); ?>
	</p>
	<?php
	return;
endif;
?>

<table class="goodbids-top-referrers-table">
	<thead>
		<tr>
			<td><?php esc_html_e( 'User', 'goodbids' ); ?></td>
			<td><?php esc_html_e( 'Count', 'goodbids' ); ?></td>
		</tr>
	</thead>

	<tbody>
		<?php foreach ( $results as $result ) : ?>
			<tr>
				<?php $user = get_user_by( 'ID', $result['id'] ); ?>
				<td><?php echo esc_html( $user->user_login ); ?></td>
				<td><?php echo esc_html( $result['counted'] ); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
