<?php
/**
 * Pattern: Support Request Page Template
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Network\Nonprofit;

$nonprofit = new Nonprofit( get_current_blog_id() );
?>
<!-- wp:acf/support-request-form {"name":"acf/support-request-form","mode":"preview"} -->
<!-- wp:heading {"textColor":"base-2"} -->
<h2 class="wp-block-heading has-base-2-color has-text-color"><?php esc_html_e( 'Request Support from', 'goodbids' ); ?> <?php echo esc_html( $nonprofit->get_name() ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"base-2"} -->
<p class="has-base-2-color has-text-color"><?php esc_html_e( 'Use the form below to submit a support request to this Nonprofit. Your submission will be visible to administrators for this Nonprofit site as well as the GOODBIDS support team. We will respond as soon as we can.', 'goodbids' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:acf/support-request-form -->
