<?php
/**
 * Class Miniorange_Oauth_20_Server_Postman_Collection_Download
 *
 * @package Miniorange_Oauth_20_Server
 */

/**
 * Class Miniorange_Oauth_20_Server_Postman_Collection_Download
 *
 * This class handles the download of Postman Collection file.
 */
class Miniorange_Oauth_20_Server_Postman_Collection_Download {
	/**
	 * Summary of postman_collection_download
	 *
	 * Allows to download a Postman Collection file for testing configuration.
	 *
	 * @return void
	 */
	public function postman_collection_download() {

		global $wpdb;
		$client_id = isset( $_REQUEST['client'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['client'] ) ) : false; //phpcs:ignore WordPress.Security.NonceVerification -- This is used to get the client_id from the client
		if ( false === $client_id ) {
			wp_die( 'Invalid Client.' );
		}

		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-utils.php';
		$mo_utils = new Miniorange_Oauth_20_Server_Utils();

		$client        = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->base_prefix . 'moos_oauth_clients where client_id = %s', $client_id ), ARRAY_A ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$client_secret = $mo_utils->mo_oauth_server_decrypt( $client['client_secret'], $client['client_name'] );

		// store home url and rest prefix in a variable
		// rest prefix eg. wp-json.
		global $moos_home_url_plus_rest_prefix;

		$access_token_url = $moos_home_url_plus_rest_prefix . '/moserver/token';
		$auth_url         = $moos_home_url_plus_rest_prefix . '/moserver/authorize';
		$resource_url     = $moos_home_url_plus_rest_prefix . '/moserver/resource';
		$protocol         = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$path_url = explode( '/', esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
		}

		$path = '';
		foreach ( $path_url as $path_url_segment ) {
			if ( 'wp-admin' === $path_url_segment ) {
				break;
			}
			if ( ! empty( $path_url_segment ) ) {
				$path = $path . '"' . $path_url_segment . '",';
			}
		}

		// handle url structure for plain permalinks.
		$permalink_structure = get_option( 'permalink_structure' );

		if ( $permalink_structure ) {
			$path = $path . '"' . rest_get_url_prefix() . '",';
			$path = $path . '"moserver", "resource"';
		}

		$path = rtrim( $path, ',' );

		$query = '';

		if ( ! $permalink_structure ) {
			$query = '{
				"key": "rest_route",
				"value": "/moserver/resource"
			}';
		}

		// Isset check, sanitize and unslash the server name from super global variable.
		$server_name = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';

		$postman_json_data = '{
			"info": {
				"_postman_id": "2821a659-7b56-48be-9e72-ff95a971ee7f",
				"name": "miniOrange OAuth Server",
				"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
			},
			"item": [
				{
					"name": "Authorization Grant",
					"request": {
						"auth": {
							"type": "oauth2",
							"oauth2": [
								{
									"key": "clientSecret",
									"value": "' . $client_secret . '",
									"type": "string"
								},
								{
									"key": "clientId",
									"value": "' . $client_id . '",
									"type": "string"
								},
								{
									"key": "scope",
									"value": "openid profile email",
									"type": "string"
								},
								{
									"key": "accessTokenUrl",
									"value": "' . $access_token_url . '",
									"type": "string"
								},
								{
									"key": "useBrowser",
									"value": true,
									"type": "boolean"
								},
								{
									"key": "state",
									"value": "test",
									"type": "string"
								},
								{
									"key": "authUrl",
									"value": "' . $auth_url . '",
									"type": "string"
								},
								{
									"key": "redirect_uri",
									"value": "",
									"type": "string"
								},
								{
									"key": "tokenName",
									"value": "' . $client['client_name'] . '",
									"type": "string"
								},
								{
									"key": "addTokenTo",
									"value": "header",
									"type": "string"
								}
							]
						},
						"method": "GET",
						"header": [],
						"url": {
							"raw": "' . $resource_url . '",
							"protocol": "' . $protocol . '",
							"host": [
								"' . $server_name . '"
							],
							"path": [' . $path . '],
							"query": [' . $query . ']
						}
					},
					"response": []
				}
			]
		}';

		header( 'Content-Disposition: attachment; filename="miniOrange_OAuth_server_collection.json"' );
		header( 'Content-Type: application/json' );
		header( 'Content-Length: ' . strlen( $postman_json_data ) );
		header( 'Connection: close' );

		echo $postman_json_data; //phpcs:ignore WordPress.Security.EscapeOutput -- This is a JSON data to be put into a file to be downloaded
		exit();
	}


}
