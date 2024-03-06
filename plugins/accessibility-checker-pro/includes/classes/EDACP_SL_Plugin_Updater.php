<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Allows plugins to use their own update API.
 *
 * @author Easy Digital Downloads
 * @version 1.9.2
 */
class EDACP_SL_Plugin_Updater {

	// phpcs:disable Squiz.Commenting.VariableComment.Missing
	private $api_url     = '';
	private $api_data    = array();
	private $plugin_file = '';
	private $name        = '';
	private $slug        = '';
	private $version     = '';
	private $wp_override = false;
	private $beta        = false;
	private $failed_request_cache_key;
	// phpcs:enable Squiz.Commenting.VariableComment.Missing

	/**
	 * Class constructor.
	 *
	 * @uses plugin_basename()
	 * @uses hook()
	 *
	 * @param string $_api_url     The URL pointing to the custom API endpoint.
	 * @param string $_plugin_file Path to the plugin file.
	 * @param array  $_api_data    Optional data to send with API calls.
	 */
	public function __construct( $_api_url, $_plugin_file, $_api_data = null ) {

		global $edd_plugin_data;

		$this->api_url                  = trailingslashit( $_api_url );
		$this->api_data                 = $_api_data;
		$this->plugin_file              = $_plugin_file;
		$this->name                     = plugin_basename( $_plugin_file );
		$this->slug                     = basename( $_plugin_file, '.php' );
		$this->version                  = $_api_data['version'];
		$this->wp_override              = isset( $_api_data['wp_override'] ) ? (bool) $_api_data['wp_override'] : false;
		$this->beta                     = ! empty( $this->api_data['beta'] ) ? true : false;
		$this->failed_request_cache_key = 'edd_sl_failed_http_' . md5( $this->api_url );
		$edd_plugin_data[ $this->slug ] = $this->api_data;

		/**
		 * Fires after the $edd_plugin_data is setup.
		 *
		 * @since x.x.x
		 *
		 * @param array $edd_plugin_data Array of EDD SL plugin data.
		 */
		do_action( 'post_EDACP_SL_Plugin_Updater_setup', $edd_plugin_data ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.NotLowercase

		// Set up hooks.
		$this->init();
	}

	/**
	 * Set up WordPress filters to hook into WP's update process.
	 *
	 * @uses add_filter()
	 *
	 * @return void
	 */
	public function init() {

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugins_api_filter' ), 10, 3 );
		add_action( 'after_plugin_row', array( $this, 'show_update_notification' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'show_changelog' ) );
	}

	/**
	 * Check for Updates at the defined API endpoint and modify the update array.
	 *
	 * This function dives into the update API just when WordPress creates its update array,
	 * then adds a custom API call and injects the custom plugin data retrieved from the API.
	 * It is reassembled from parts of the native WordPress plugin update code.
	 * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
	 *
	 * @uses api_request()
	 *
	 * @param array $_transient_data Update array build by WordPress.
	 * @return array Modified update array with custom plugin data.
	 */
	public function check_update( $_transient_data ) {
		if ( ! is_object( $_transient_data ) ) {
			$_transient_data = new stdClass();
		}

		if ( ! empty( $_transient_data->response ) && ! empty( $_transient_data->response[ $this->name ] ) && false === $this->wp_override ) {
			return $_transient_data;
		}

		$current = $this->get_repo_api_data();
		if ( false !== $current && is_object( $current ) && isset( $current->new_version ) ) {
			if ( version_compare( $this->version, $current->new_version, '<' ) ) {
				$_transient_data->response[ $this->name ] = $current;
			} else {
				// Populating the no_update information is required to support auto-updates in WordPress 5.5.
				$_transient_data->no_update[ $this->name ] = $current;
			}
		}
		$_transient_data->last_checked           = time();
		$_transient_data->checked[ $this->name ] = $this->version;

		return $_transient_data;
	}

	/**
	 * Get repo API data from store.
	 * Save to cache.
	 *
	 * @return \stdClass
	 */
	public function get_repo_api_data() {
		$version_info = $this->get_cached_version_info();

		if ( false === $version_info ) {
			$version_info = $this->api_request(
				'plugin_latest_version',
				array(
					'slug' => $this->slug,
					'beta' => $this->beta,
				)
			);
			if ( ! $version_info ) {
				return false;
			}

			// This is required for your plugin to support auto-updates in WordPress 5.5.
			$version_info->plugin = $this->name;
			$version_info->id     = $this->name;
			$version_info->tested = $this->get_tested_version( $version_info );

			$this->set_version_info_cache( $version_info );
		}

		return $version_info;
	}

	/**
	 * Gets the plugin's tested version.
	 *
	 * @since 1.9.2
	 * @param object $version_info The version info object.
	 * @return null|string
	 */
	private function get_tested_version( $version_info ) {

		// There is no tested version.
		if ( empty( $version_info->tested ) ) {
			return null;
		}

		// Strip off extra version data so the result is x.y or x.y.z.
		list( $current_wp_version ) = explode( '-', get_bloginfo( 'version' ) );

		// The tested version is greater than or equal to the current WP version, no need to do anything.
		if ( version_compare( $version_info->tested, $current_wp_version, '>=' ) ) {
			return $version_info->tested;
		}
		$current_version_parts = explode( '.', $current_wp_version );
		$tested_parts          = explode( '.', $version_info->tested );

		// The current WordPress version is x.y.z, so update the tested version to match it.
		if ( isset( $current_version_parts[2] ) && $current_version_parts[0] === $tested_parts[0] && $current_version_parts[1] === $tested_parts[1] ) {
			$tested_parts[2] = $current_version_parts[2];
		}

		return implode( '.', $tested_parts );
	}

	/**
	 * Show the update notification on multisite subsites.
	 *
	 * @param string $file   Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin Plugin data array.
	 */
	public function show_update_notification( $file, $plugin ) {

		// Return early if in the network admin, or if this is not a multisite install.
		if ( is_network_admin() || ! is_multisite() ) {
			return;
		}

		// Allow single site admins to see that an update is available.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		if ( $this->name !== $file ) {
			return;
		}

		// Do not print any message if update does not exist.
		$update_cache = get_site_transient( 'update_plugins' );

		if ( ! isset( $update_cache->response[ $this->name ] ) ) {
			if ( ! is_object( $update_cache ) ) {
				$update_cache = new stdClass();
			}
			$update_cache->response[ $this->name ] = $this->get_repo_api_data();
		}

		// Return early if this plugin isn't in the transient->response or if the site is running the current or newer version of the plugin.
		if ( empty( $update_cache->response[ $this->name ] )
			|| version_compare( $this->version, $update_cache->response[ $this->name ]->new_version, '>=' )
		) {
			return;
		}
		?>
		<tr
			class="plugin-update-tr <?php echo in_array( $this->name, $this->get_active_plugins(), true ) ? 'active' : 'inactive'; ?>"
			id="<?php echo esc_attr( $this->slug ); ?>-update"
			data-slug="<?php echo esc_attr( $this->slug ); ?>"
			data-plugin="<?php echo esc_attr( $file ); ?>"
		>
			<td colspan="3" class="plugin-update colspanchange">
				<div class="update-message notice inline notice-warning notice-alt">
					<p>
						<?php
						$changelog_link = '';
						if ( ! empty( $update_cache->response[ $this->name ]->sections->changelog ) ) {
							$changelog_link = add_query_arg(
								array(
									'edd_sl_action' => 'view_plugin_changelog',
									'plugin'        => rawurlencode( $this->name ),
									'slug'          => rawurlencode( $this->slug ),
									'TB_iframe'     => 'true',
									'width'         => 77,
									'height'        => 911,
								),
								self_admin_url( 'index.php' )
							);
						}
						$update_link = add_query_arg(
							array(
								'action' => 'upgrade-plugin',
								'plugin' => rawurlencode( $this->name ),
							),
							self_admin_url( 'update.php' )
						);
						?>

						<?php
						printf(
							/* translators: the plugin name. */
							esc_html__( 'There is a new version of %1$s available.', 'edacp' ),
							esc_html( $plugin['Name'] )
						);

						if ( ! current_user_can( 'update_plugins' ) ) {
							echo ' ';
							esc_html_e( 'Contact your network administrator to install the update.', 'edacp' );
						} elseif ( empty( $update_cache->response[ $this->name ]->package ) && ! empty( $changelog_link ) ) {
							echo ' ';
							?>
							<a target="_blank" class="thickbox open-plugin-details-modal" href="<?php echo esc_url( $changelog_link ); ?>">
								<?php 
								printf(
									/* translators: %s: the new plugin version. */
									esc_html__( 'View version %s details.', 'edacp' ),
									esc_html( $update_cache->response[ $this->name ]->new_version )
								);
								?>
							</a>
							<?php
						} elseif ( ! empty( $changelog_link ) ) {
							echo ' ';
							printf(
								/* translators: %1$s: link with text "View version %s details. %2$s: link with text "update now". */
								esc_html__( '%1$s or %2$s', 'edacp' ),
								'<a target="_blank" class="thickbox open-plugin-details-modal" href="' . esc_url( $changelog_link ) . '">' .
									sprintf(
										/* translators: %s: the new plugin version. */
										esc_html__( 'View version %s details', 'edacp' ),
										esc_html( $update_cache->response[ $this->name ]->new_version )
									) .
								'</a>',
								'<a target="_blank" class="update-link" href="' . esc_url( wp_nonce_url( $update_link, 'upgrade-plugin_' . $file ) ) . '">' .
									esc_html__( 'update now', 'edacp' ) .
								'</a>'
							);
						} else {
							?>
							<a target="_blank" class="update-link" href="<?php echo esc_url( wp_nonce_url( $update_link, 'upgrade-plugin_' . $file ) ); ?>">
								<?php esc_html_e( 'Update now.', 'edacp' ); ?>
							</a>
							<?php
						}
						?>

						<?php do_action( "in_plugin_update_message-{$file}", $plugin, $plugin ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores ?>
					</p>
				</div>
			</td>
		</tr>
		<?php
	}

	/**
	 * Gets the plugins active in a multisite network.
	 *
	 * @return array
	 */
	private function get_active_plugins() {
		$active_plugins         = (array) get_option( 'active_plugins' );
		$active_network_plugins = (array) get_site_option( 'active_sitewide_plugins' );

		return array_merge( $active_plugins, array_keys( $active_network_plugins ) );
	}

	// phpcs:disable Squiz.Commenting.FunctionComment.MissingParamComment
	/**
	 * Updates information on the "View version x.x details" page with custom data.
	 *
	 * @uses api_request()
	 *
	 * @param mixed  $_data
	 * @param string $_action 
	 * @param object $_args
	 * @return object $_data
	 */
	public function plugins_api_filter( $_data, $_action = '', $_args = null ) {

		if ( 'plugin_information' !== $_action ) {
			return $_data;
		}

		if ( ! isset( $_args->slug ) || ( $_args->slug !== $this->slug ) ) {
			return $_data;
		}

		$to_send = array(
			'slug'   => $this->slug,
			'is_ssl' => is_ssl(),
			'fields' => array(
				'banners' => array(),
				'reviews' => false,
				'icons'   => array(),
			),
		);

		// Get the transient where we store the api request for this plugin for 24 hours.
		$edd_api_request_transient = $this->get_cached_version_info();

		// If we have no transient-saved value, run the API, set a fresh transient with the API value, and return that value too right now.
		if ( empty( $edd_api_request_transient ) ) {

			$api_response = $this->api_request( 'plugin_information', $to_send );

			// Expires in 3 hours.
			$this->set_version_info_cache( $api_response );

			if ( false !== $api_response ) {
				$_data = $api_response;
			}
		} else {
			$_data = $edd_api_request_transient;
		}

		// Convert sections into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->sections ) && ! is_array( $_data->sections ) ) {
			$_data->sections = $this->convert_object_to_array( $_data->sections );
		}

		// Convert banners into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->banners ) && ! is_array( $_data->banners ) ) {
			$_data->banners = $this->convert_object_to_array( $_data->banners );
		}

		// Convert icons into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->icons ) && ! is_array( $_data->icons ) ) {
			$_data->icons = $this->convert_object_to_array( $_data->icons );
		}

		// Convert contributors into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->contributors ) && ! is_array( $_data->contributors ) ) {
			$_data->contributors = $this->convert_object_to_array( $_data->contributors );
		}

		if ( ! isset( $_data->plugin ) ) {
			$_data->plugin = $this->name;
		}

		return $_data;
	}
	// phpcs:enable Squiz.Commenting.FunctionComment.MissingParamComment

	/**
	 * Convert some objects to arrays when injecting data into the update API
	 *
	 * Some data like sections, banners, and icons are expected to be an associative array, however due to the JSON
	 * decoding, they are objects. This method allows us to pass in the object and return an associative array.
	 *
	 * @since 3.6.5
	 *
	 * @param stdClass $data The data to convert to an array.
	 *
	 * @return array
	 */
	private function convert_object_to_array( $data ) {
		if ( ! is_array( $data ) && ! is_object( $data ) ) {
			return array();
		}
		$new_data = array();
		foreach ( $data as $key => $value ) {
			$new_data[ $key ] = is_object( $value ) ? $this->convert_object_to_array( $value ) : $value;
		}

		return $new_data;
	}

	/**
	 * Disable SSL verification in order to prevent download update failures
	 *
	 * @param array  $args The HTTP request arguments.
	 * @param string $url  The request URL.
	 * @return object $array
	 */
	public function http_request_args( $args, $url ) {

		if ( strpos( $url, 'https://' ) !== false && strpos( $url, 'edd_action=package_download' ) ) {
			$args['sslverify'] = $this->verify_ssl();
		}
		return $args;
	}

	/**
	 * Calls the API and, if successfull, returns the object delivered by the API.
	 *
	 * @uses get_bloginfo()
	 * @uses wp_remote_post()
	 * @uses is_wp_error()
	 *
	 * @param string $_action The requested action.
	 * @param array  $_data   Parameters for the API action.
	 * @return false|object|void
	 */
	private function api_request( $_action, $_data ) {
		$data = array_merge( $this->api_data, $_data );

		if ( $data['slug'] !== $this->slug ) {
			return;
		}

		// Don't allow a plugin to ping itself.
		if ( trailingslashit( home_url() ) === $this->api_url ) {
			return false;
		}

		if ( $this->request_recently_failed() ) {
			return false;
		}

		return $this->get_version_from_remote();
	}

	/**
	 * Determines if a request has recently failed.
	 *
	 * @since 1.9.1
	 *
	 * @return bool
	 */
	private function request_recently_failed() {
		$failed_request_details = get_option( $this->failed_request_cache_key );

		// Request has never failed.
		if ( empty( $failed_request_details ) || ! is_numeric( $failed_request_details ) ) {
			return false;
		}

		/*
		 * Request previously failed, but the timeout has expired.
		 * This means we're allowed to try again.
		 */
		if ( time() > $failed_request_details ) {
			delete_option( $this->failed_request_cache_key );

			return false;
		}

		return true;
	}

	/**
	 * Logs a failed HTTP request for this API URL.
	 * We set a timestamp for 1 hour from now. This prevents future API requests from being
	 * made to this domain for 1 hour. Once the timestamp is in the past, API requests
	 * will be allowed again. This way if the site is down for some reason we don't bombard
	 * it with failed API requests.
	 *
	 * @see EDACP_SL_Plugin_Updater::request_recently_failed
	 *
	 * @since 1.9.1
	 */
	private function log_failed_request() {
		update_option( $this->failed_request_cache_key, strtotime( '+1 hour' ) );
	}

	/**
	 * If available, show the changelog for sites in a multisite install.
	 */
	public function show_changelog() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_REQUEST['edd_sl_action'] ) || 'view_plugin_changelog' !== $_REQUEST['edd_sl_action'] ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_REQUEST['plugin'] ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_REQUEST['slug'] ) || $this->slug !== $_REQUEST['slug'] ) {
			return;
		}

		if ( ! current_user_can( 'update_plugins' ) ) {
			wp_die(
				esc_html__( 'You do not have permission to install plugin updates', 'edacp' ),
				esc_html__( 'Error', 'edacp' ),
				array( 'response' => 403 )
			);
		}

		$version_info = $this->get_repo_api_data();
		if ( isset( $version_info->sections ) ) {
			$sections = $this->convert_object_to_array( $version_info->sections );
			if ( ! empty( $sections['changelog'] ) ) {
				echo '<div style="background:#fff;padding:10px;">' . wp_kses_post( $sections['changelog'] ) . '</div>';
			}
		}

		exit;
	}

	/**
	 * Gets the current version information from the remote site.
	 *
	 * @return array|false
	 */
	private function get_version_from_remote() {
		$api_params = array(
			'edd_action'  => 'get_version',
			'license'     => ! empty( $this->api_data['license'] ) ? $this->api_data['license'] : '',
			'item_name'   => isset( $this->api_data['item_name'] ) ? $this->api_data['item_name'] : false,
			'item_id'     => isset( $this->api_data['item_id'] ) ? $this->api_data['item_id'] : false,
			'version'     => isset( $this->api_data['version'] ) ? $this->api_data['version'] : false,
			'slug'        => $this->slug,
			'author'      => $this->api_data['author'],
			'url'         => home_url(),
			'beta'        => $this->beta,
			'php_version' => phpversion(),
			'wp_version'  => get_bloginfo( 'version' ),
		);

		/**
		 * Filters the parameters sent in the API request.
		 *
		 * @param array  $api_params        The array of data sent in the request.
		 * @param array  $this->api_data    The array of data set up in the class constructor.
		 * @param string $this->plugin_file The full path and filename of the file.
		 */
		$api_params = apply_filters( 'EDACP_SL_Plugin_Updater_api_params', $api_params, $this->api_data, $this->plugin_file ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.NotLowercase

		$request = wp_remote_post(
			$this->api_url,
			array(
				'timeout'   => 15, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout -- 15 seconds is necessary here for now.
				'sslverify' => $this->verify_ssl(),
				'body'      => $api_params,
			)
		);

		if ( is_wp_error( $request ) || ( 200 !== wp_remote_retrieve_response_code( $request ) ) ) {
			$this->log_failed_request();

			return false;
		}

		$request = json_decode( wp_remote_retrieve_body( $request ) );

		if ( $request && isset( $request->sections ) ) {
			$request->sections = maybe_unserialize( $request->sections );
		} else {
			$request = false;
		}

		if ( $request && isset( $request->banners ) ) {
			$request->banners = maybe_unserialize( $request->banners );
		}

		if ( $request && isset( $request->icons ) ) {
			$request->icons = maybe_unserialize( $request->icons );
		}

		if ( ! empty( $request->sections ) ) {
			foreach ( $request->sections as $key => $section ) {
				$request->$key = (array) $section;
			}
		}

		return $request;
	}

	/**
	 * Get the version info from the cache, if it exists.
	 *
	 * @param string $cache_key The cache key to use.
	 * @return object
	 */
	public function get_cached_version_info( $cache_key = '' ) {

		if ( empty( $cache_key ) ) {
			$cache_key = $this->get_cache_key();
		}

		$cache = get_option( $cache_key );

		// Cache is expired.
		if ( empty( $cache['timeout'] ) || time() > $cache['timeout'] ) {
			return false;
		}

		// We need to turn the icons into an array, thanks to WP Core forcing these into an object at some point.
		$cache['value'] = json_decode( $cache['value'] );
		if ( ! empty( $cache['value']->icons ) ) {
			$cache['value']->icons = (array) $cache['value']->icons;
		}

		return $cache['value'];
	}

	/**
	 * Adds the plugin version information to the database.
	 *
	 * @param string $value     The value to store in the cache.
	 * @param string $cache_key The cache key to use.
	 */
	public function set_version_info_cache( $value = '', $cache_key = '' ) {

		if ( empty( $cache_key ) ) {
			$cache_key = $this->get_cache_key();
		}

		$data = array(
			'timeout' => strtotime( '+3 hours', time() ),
			'value'   => wp_json_encode( $value ),
		);

		update_option( $cache_key, $data, 'no' );

		// Delete the duplicate option.
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize -- this is a unique case.
		delete_option( 'edd_api_request_' . md5( serialize( $this->slug . $this->api_data['license'] . $this->beta ) ) );
	}

	/**
	 * Returns if the SSL of the store should be verified.
	 *
	 * @since  1.6.13
	 * @return bool
	 */
	private function verify_ssl() {
		return (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true, $this );
	}

	/**
	 * Gets the unique key (option name) for a plugin.
	 *
	 * @since 1.9.0
	 * @return string
	 */
	private function get_cache_key() {
		$string = $this->slug . $this->api_data['license'] . $this->beta;

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize -- this is a unique case.
		return 'edd_sl_' . md5( serialize( $string ) );
	}
}
