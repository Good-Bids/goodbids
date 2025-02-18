<?php
/**
 * Local Environment Variables
 *
 * @package GoodBids
 */

/**
 * Copy and paste this file, renaming it to "vip-env-vars.local.php"
 *
 * Fill in any TODO with the appropriate value
 *
 * Do not store sensitive data in this example file.
 * Feel free to pre-fill environment variables that contain
 * non-sensitive data, but env vars that contain things like
 * passwords and api keys should be left as TODOs
 */

/**
 * Auctioneer Environment Variables
 */
defined( 'VIP_ENV_VAR_GOODBIDS_AUCTIONEER_URL_LOCAL' ) || define( 'VIP_ENV_VAR_GOODBIDS_AUCTIONEER_URL_LOCAL', 'http://localhost:3000' );
defined( 'VIP_ENV_VAR_GOODBIDS_AUCTIONEER_URL_DEVELOP' ) || define( 'VIP_ENV_VAR_GOODBIDS_AUCTIONEER_URL_DEVELOP', 'https://goodbids-node-develop.go-vip.net' );
defined( 'VIP_ENV_VAR_GOODBIDS_AUCTIONEER_URL_STAGING' ) || define( 'VIP_ENV_VAR_GOODBIDS_AUCTIONEER_URL_STAGING', 'https://goodbids-node-staging.go-vip.net' );
defined( 'VIP_ENV_VAR_GOODBIDS_AUCTIONEER_URL_PRODUCTION' ) || define( 'VIP_ENV_VAR_GOODBIDS_AUCTIONEER_URL_PRODUCTION', 'https://goodbids-node.go-vip.net' );

/**
 * Current Development Environment can be obtained by using:
 * `vip config envvar get API_KEY -a goodbids -e develop`
 *
 * TODO
 */
defined( 'VIP_ENV_VAR_GOODBIDS_AUCTIONEER_API_KEY' ) || define( 'VIP_ENV_VAR_GOODBIDS_AUCTIONEER_API_KEY', 'YOUR_VALUE_HERE' );

/**
 * Third-Party Plugin Constants
 *
 * TODO
 */
// ACF Pro
defined( 'VIP_ENV_VAR_GOODBIDS_ACF_LICENSE_KEY' ) || define( 'VIP_ENV_VAR_GOODBIDS_ACF_LICENSE_KEY', 'YOUR_VALUE_HERE' );

// Accessibility Checker Pro
defined( 'GOODBIDS_EDACP_LICENSE_KEY' ) || define( 'GOODBIDS_EDACP_LICENSE_KEY', 'YOUR_VALUE_HERE' );
