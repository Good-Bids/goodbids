<?php

namespace WPMailSMTP\Pro\Admin;

use WPMailSMTP\Pro\Admin\Pages\MiscTab;

/**
 * Class Area registers and process all wp-admin display functionality.
 *
 * @since 4.0.0
 */
class Area {

	/**
	 * Assign all hooks to proper places.
	 *
	 * @since 4.0.0
	 */
	public function hooks() {

		// Admin pages.
		add_filter( 'wp_mail_smtp_admin_get_pages', [ $this, 'admin_get_pages' ] );
	}

	/**
	 * Replace Lite's Misc tab with Pro version.
	 *
	 * @since 4.0.0
	 *
	 * @param array $pages List of admin pages.
	 *
	 * @return array
	 */
	public function admin_get_pages( $pages ) {

		$pages['misc'] = new MiscTab();

		return $pages;
	}
}
