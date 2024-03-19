<?php
/**
 * Support Request Email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>

<p>
	<?php
	printf(
		/* translators: %1$s: Site Title  */
		esc_html__( 'A new support request has been submitted for the %1$s GOODBIDS site that needs your attention.', 'goodbids' ),
		'{site_title}'
	);
	?>
</p>

<div style="margin-bottom: 40px;">
	<table cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<tbody>
			<tr>
				<td class="th" scope="col"><?php esc_html_e( 'Author', 'goodbids' ); ?></td>
				<td class="td" scope="col">{request.user.name}</td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Email', 'goodbids' ); ?></td>
				<td class="td">{request.user.email}</td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Type', 'goodbids' ); ?></td>
				<td class="td">{request.type}</td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Subject', 'goodbids' ); ?></td>
				<td class="td">{request.nature}</td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Request', 'goodbids' ); ?></td>
				<td class="td">{request.request}</td>
			</tr>
		</tbody>
	</table>
</div>

<p>
	<?php
	printf(
		'%s <a href="%s">%s</a> %s.',
		esc_html__( 'Please visit the', 'goodbids' ),
		'{request.url}',
		esc_html__( 'Support Request panel', 'goodbids' ),
		esc_html__( 'to see more details', 'goodbids' )
	);
	?>
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
