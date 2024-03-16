<?php
/**
 * GoodBids Network Bidders
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Admin\ScreenOptions;
use WP_Screen;

/**
 * Network Admin Bidders Class
 *
 * @since 1.0.0
 */
class Bidders {

	/**
	 * Bidders Page Slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-bidders';

	/**
	 * @since 1.0.0
	 * @var ?ScreenOptions
	 */
	private ?ScreenOptions $screen_options = null;

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize Screen Options.
		$this->init_screen_options();
	}

	/**
	 * Initialize Screen Options
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_screen_options(): void {
		add_action(
			'admin_init',
			function () {
				$this->screen_options = new ScreenOptions( self::PAGE_SLUG );
				$this->screen_options->set_options(
					[
						'bidders_table_limit' => [
							'label'   => esc_html__( 'Bidders Per Page', 'goodbids' ),
							'type'    => 'number',
							'class'   => 'small-text',
							'default' => 20,
						],
					]
				);

				add_filter(
					'goodbids_bidders_table_per_page',
					fn () => $this->screen_options->get_option( 'bidders_table_limit' )
				);
			}
		);

		add_action(
			'current_screen',
			function ( WP_Screen $screen ) {
				if ( ! str_contains( $screen->id, self::PAGE_SLUG ) ) {
					return;
				}

				$this->screen_options->init( $screen->id );
			}
		);
	}
}
