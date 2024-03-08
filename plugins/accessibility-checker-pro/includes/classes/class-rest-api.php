<?php
/**
 * Class file for REST api
 *
 * @package Accessibility_Checker_Pro
 */

namespace EDACP;

/**
 * Class that initializes and handles the REST api
 */
class REST_Api {

	/**
	 * If class has already been initialized.
	 *
	 * @var boolean
	 */
	private static $initialized = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( EDACP_KEY_VALID === true ) {
			if ( ! self::$initialized ) {
				$this->initialize();
			}
		}
	}

	/**
	 * Adds the actions.
	 */
	private function initialize() {
		$ns      = 'accessibility-checker-pro/';
		$version = 'v1';

	
		add_action(
			'rest_api_init',
			function () use ( $ns, $version ) {
				register_rest_route(
					$ns . $version,
					'/test',
					array(
						'methods'             => array( 'GET', 'POST' ),
						'callback'            => function () {
		
							$messages = array();
							$messages['time'] = time();
							$messages['perms'] = current_user_can( 'edit_posts' );
						
						
							return new \WP_REST_Response( array( 'messages' => $messages ), 200 );
						},
						'permission_callback' => function () {
							return true;
						},
					) 
				);
			} 
		);

		add_action(
			'rest_api_init',
			function () use ( $ns, $version ) {
				register_rest_route(
					$ns . $version,
					'/scheduled-scan-url',
					array(
						'methods'             => 'GET',
						'callback'            => array( $this, 'get_next_scan_url' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_others_posts' );
						},
					)
				);
			}
		);

		add_action(
			'rest_api_init',
			function () use ( $ns, $version ) {
				register_rest_route(
					$ns . $version,
					'/start-new-scan',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'start_new_scan' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_others_posts' );
						},
					)
				);
			}
		);

		add_action(
			'rest_api_init',
			function () use ( $ns, $version ) {
				register_rest_route(
					$ns . $version,
					'/cancel-current-scan',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'cancel_current_scan' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_others_posts' );
						},
					)
				);
			}
		);

		add_action(
			'rest_api_init',
			function () use ( $ns, $version ) {
				register_rest_route(
					$ns . $version,
					'/cancel-current-php-scan',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'cancel_current_php_scan' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_others_posts' );
						},
					)
				);
			}
		);
	
		add_action(
			'rest_api_init',
			function () use ( $ns, $version ) {
				register_rest_route(
					$ns . $version,
					'/scan-stats',
					array(
						'methods'             => 'GET',
						'callback'            => array( $this, 'get_scan_stats' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_others_posts' );
						},
					)
				);
			}
		);
	}

	/**
	 * REST handler that returns the preview url of the next post scheduled to be scanned (js)
	 *
	 * @param  WP_REST_Request $request .
	 * @return WP_REST_Response
	 */
	public function get_next_scan_url( $request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found, VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$messages = array();

		$scans = new \EDACP\Scans();

		$all_pendings = array_merge(
			$scans->get_never_scanned(),
			$scans->get_pending()
		);

		$state = $scans->scan_state();
		if ( \EDACP\Scans::SCAN_STATE_QUEUED === $state
			|| \EDACP\Scans::SCAN_STATE_PHP_SCAN_RUNNING === $state
			|| \EDACP\Scans::SCAN_STATE_ALL_CANCELED === $state

		) {
			$messages[] = sprintf(
				__( 'Scan state is: $s', 'edacp' ),
				$state
			);

			return new \WP_REST_Response(
				array(
					'data'          => array(
						'scanStats' => array(
							'js' => array(
								'state'   => $state,
								'pending' => -1,
							),
						),
					),
					'body_response' => array(),
				)
			);

		}
		
		$js_stats = $scans->get_stats();
		
		if ( count( $all_pendings ) ) {

			$messages[] = __( 'There is a post to scan.', 'edacp' );

			$pending = $all_pendings[0];
		  
			$redir_test_url = get_preview_post_link( $pending['id'] );
			$redir_test_url = str_replace( '?preview=true', '', $redir_test_url );
			$redir_test_url = str_replace( '&preview=true', '', $redir_test_url );
					

			return new \WP_REST_Response(
				array(
					'data'          => array( 
						'postId'       => $pending['id'],
						'liveUrl'      => get_permalink( $pending['id'] ),
						'redirTestUrl' => $redir_test_url,
						'scanUrl'      => get_preview_post_link( $pending['id'] ),
						'messages'     => $messages,
						'scanStats'    => array(
							'js' => $js_stats,
						),
					),

					'body_response' => array(
						'messages' => $messages,
					),
				)
			);
		} else {
			$messages[] = __( 'All posts have been scanned.', 'edacp' );

			return new \WP_REST_Response(
				array(
					'data'          => array(
						'scanStats' => array(
							'js' => $js_stats,
						),
					),
					'body_response' => array( 'messages' => $messages ),
				)
			);
		}
	}

	/**
	 * REST handler that starts a new scan
	 *
	 * @param  WP_REST_Request $request .
	 * @return WP_REST_Response
	 */
	public function start_new_scan( $request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found, VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable

		$scans  = new \EDACP\Scans();
		$retval = $scans->start_php_scan();
	
		return new \WP_REST_Response(
			array(
				'body_response' => array(
					'ok' => true === $retval,
				),
			)
		);
	}


	/**
	 * REST handler that cancels the current php scan
	 *
	 * @param  WP_REST_Request $request .
	 * @return WP_REST_Response
	 */
	public function cancel_current_php_scan( $request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found, VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable

		$scans  = new \EDACP\Scans();
		$cancel = $scans->cancel_current_scan( false );

		return new \WP_REST_Response(
			array(
				'body_response' => array(
					'canceled' => false === $cancel['error'],
					'message'  => $cancel['message'],
				),
			)
		);
	}

	/**
	 * REST handler that cancels the current scan (both php and js)
	 *
	 * @param  WP_REST_Request $request .
	 * @return WP_REST_Response
	 */
	public function cancel_current_scan( $request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found, VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable

		$scans  = new \EDACP\Scans();
		$cancel = $scans->cancel_current_scan();

		return new \WP_REST_Response(
			array(
				'body_response' => array(
					'canceled' => false === $cancel['error'],
					'message'  => $cancel['message'],
				),
			)
		);
	}

	/**
	 * REST handler that gets stats about the most recently scheduled scan 
	 * or a manually run fullscan
	 * 
	 * @param  WP_REST_Request $request .
	 * @return WP_REST_Response
	 */
	public function get_scan_stats( $request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
   
		$scans     = new \EDACP\Scans();
		$php_stats = $scans->get_stats( 'php' );
		$js_stats  = $scans->get_stats();
		
			
		return new \WP_REST_Response(
			array(
				'data'          => array(
					'scanStats' => array(
						'php' => $php_stats,
						'js'  => $js_stats,
					),
				),
				'body_response' => array(),
	
			)
		);
	}
}
