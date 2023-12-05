<?php
/**
 * VIP recommends loading all plugins for your site in code. Loading plugins
 * through code results in more control and greater consistency across
 * development environments. Using this file to do so helps load and activate
 * plugins as early as possible in the WordPress load order.
 *
 * @see https://docs.wpvip.com/how-tos/activate-plugins-through-code/
 * @see https://docs.wpvip.com/technical-references/vip-codebase/client-mu-plugins-directory/
 */

if ( defined( 'WPCOM_VIP_CLIENT_MU_PLUGIN_DIR' ) ) {
	$plugin_path = WPCOM_VIP_CLIENT_MU_PLUGIN_DIR . '/goodbids/goodbids.php';
	if ( file_exists( $plugin_path ) ) {
		require_once $plugin_path;
	}
}

/**
 * The above example requires the plugin to use a specific naming structure: /plugin-name/plugin-name.php
 * You can also specify a plugin's root file:
 * wpcom_vip_load_plugin( 'plugin-name/plugin.php' );
 *
 * wpcom_vip_load_plugin() only loads plugins from the `WP_PLUGIN_DIR` directory.
 * For client-mu-plugins use:
 * require __DIR__ . '/plugin-name/plugin-name.php';
 */
