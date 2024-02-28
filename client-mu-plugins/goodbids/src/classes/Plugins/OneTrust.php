<?php
/**
 * OneTrust Implementation
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins;

use GoodBids\Core;

/**
 * This class handles loading OneTrust assets.
 *
 * @since 1.0.0
 */
class OneTrust {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( Core::is_local_env() ) {
			return;
		}

		$this->load_assets();
	}

	/**
	 * Load OneTrust script
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function load_assets(): void {
		// default to production
		$script_url    = goodbids()->get_config( 'onetrust.production.script-url' );
		$domain_script = goodbids()->get_config( 'onetrust.production.domain-script' );

		if ( Core::is_staging_env() ) {
			$script_url    = goodbids()->get_config( 'onetrust.staging.script-url' );
			$domain_script = goodbids()->get_config( 'onetrust.staging.domain-script' );
		}

		add_action(
			'wp_head',
			function () use ( $script_url, $domain_script ) {
				printf(
					'<script src="%s" type="text/javascript" charset="UTF-8" data-domain-script="%s"></script><script type="text/javascript">function OptanonWrapper() { }</script>',
					esc_url( $script_url ),
					esc_attr( $domain_script )
				);
			}
		);
	}
}
