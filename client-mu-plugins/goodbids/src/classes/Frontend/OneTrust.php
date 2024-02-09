<?php
/**
 * OneTrust Implementation
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

/**
 * This class handles loading OneTrust assets.
 */
class OneTrust {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->get_onetrust_script();
	}


	/**
	 * OneTrust script
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function get_onetrust_script(): void {
		add_action(
			'wp_footer',
			function () {
				$onetrust_js        = 'https://cookie-cdn.cookiepro.com/scripttemplates/otSDKStub.js';
				$data_domain_script = '27e668a7-1a27-4468-a980-700744dc0a20-test';

				printf(
					'<script src="%s" type="text/javascript" charset="UTF-8" data-domain-script="%s"></script><script type="text/javascript">function OptanonWrapper() { }</script>',
					esc_url( $onetrust_js ),
					esc_attr( $data_domain_script ),
				);
			}
		);
	}
}
