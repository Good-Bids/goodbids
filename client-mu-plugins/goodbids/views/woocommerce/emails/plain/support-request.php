<?php
/**
 * Support Request email (plain text)
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var SupportRequest $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\SupportRequest;

defined( 'ABSPATH' ) || exit;

$instance->plain_text_header();

printf(
	/* translators: %1$s: Site title */
	esc_html__( 'A new support request has been submitted for the %1$s GOODBIDS site that needs your attention.', 'goodbids' ),
	'{site_title}'
);

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Author:', 'goodbids' );
echo '{request.user.name}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Email:', 'goodbids' );
echo '{request.user.email}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Type:', 'goodbids' );
echo '{request.type}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Subject:', 'goodbids' );
echo '{request.nature}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Request:', 'goodbids' );
echo '{request.request}';

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Support Request URL */
	'Please visit the Support Request panel to see more details: %1$s',
	'{request.url}'
);

$instance->plain_text_footer();
