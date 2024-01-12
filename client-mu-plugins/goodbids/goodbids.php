<?php
/**
 * Plugin Name:       GoodBids
 * Plugin URI:        https://www.goodbids.org
 * Description:       Custom functionality for GoodBids.org
 * Version:           1.0.0
 * Author:            Viget
 * Author URI:        https://www.goodbids.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       goodbids
 * Domain Path:       /languages
 *
 * @wordpress-plugin
 * @link              https://www.goodbids.org
 * @since             1.0.0
 * @package           GoodBids
 */

/* Constants */
defined( 'GOODBIDS_PLUGIN_FILE' ) || define( 'GOODBIDS_PLUGIN_FILE', __FILE__ );
defined( 'GOODBIDS_PLUGIN_PATH' ) || define( 'GOODBIDS_PLUGIN_PATH', plugin_dir_path( GOODBIDS_PLUGIN_FILE ) );
defined( 'GOODBIDS_PLUGIN_URL' ) || define( 'GOODBIDS_PLUGIN_URL', plugin_dir_url( GOODBIDS_PLUGIN_FILE ) );

/* Autoloader */
if ( file_exists( GOODBIDS_PLUGIN_PATH . 'vendor/autoload.php' ) ) {
	require GOODBIDS_PLUGIN_PATH . 'vendor/autoload.php';
}

// Helpful Dump & Die method for printing debug data to the screen
// https://gist.github.com/james2doyle/abfbd4dc5754712bac022faf4e2881a6
if (!function_exists('dd')) {
	function dd($data)
	{
		ini_set("highlight.comment", "#969896; font-style: italic");
		ini_set("highlight.default", "#FFFFFF");
		ini_set("highlight.html", "#D16568");
		ini_set("highlight.keyword", "#7FA3BC; font-weight: bold");
		ini_set("highlight.string", "#F2C47E");
		$output = highlight_string("<?php\n\n" . var_export($data, true), true);
		echo "<div style=\"background-color: #1C1E21; padding: 1rem\">{$output}</div>";
		die();
	}
}

/**
 * Plugin Function Shortcode.
 *
 * @return ?\GoodBids\Core
 */
function goodbids() : ?\GoodBids\Core {
	if ( class_exists( '\GoodBids\Core' ) ) {
		return \GoodBids\Core::get_instance();
	}

	return null;
}

// Init GoodBids plugin.
goodbids();
