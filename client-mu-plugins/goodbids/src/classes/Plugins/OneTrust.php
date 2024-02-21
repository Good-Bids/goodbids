<?php
/**
 * OneTrust Implementation
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins;

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
		$this->load_assets();
	}

	/**
	 * Load OneTrust script
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function load_assets(): void {
		add_action(
			'wp_footer',
			function () {
				$script_url    = 'https://cookie-cdn.cookiepro.com/scripttemplates/otSDKStub.js';
				$domain_script = goodbids()->get_config( 'onetrust.domain-script' );

				printf(
					'<script src="%s" type="text/javascript" charset="UTF-8" data-domain-script="%s"></script><script type="text/javascript">function OptanonWrapper() { }</script>',
					esc_url( $script_url ),
					esc_attr( $domain_script )
				);
			}
		);
	}
}
