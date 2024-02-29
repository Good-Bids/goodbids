<?php
/**
 * GoodBids Network Auctions
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Admin\ScreenOptions;

/**
 * Network Admin Auctions Class
 *
 * @since 1.0.0
 */
class Auctions {

	/**
	 * Auctions Page Slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-auctions';

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
						'auctions_table_limit' => [
							'label'   => esc_html__( 'Auctions Per Page', 'goodbids' ),
							'type'    => 'number',
							'class'   => 'small-text',
							'default' => 20,
						],
					]
				);

				add_filter(
					'goodbids_auctions_table_per_page',
					fn () => $this->screen_options->get_option( 'auctions_table_limit' )
				);
			}
		);

		add_action(
			'current_screen',
			function () {
				$current_screen = get_current_screen();

				if ( ! str_contains( $current_screen->id, self::PAGE_SLUG ) ) {
					return;
				}

				$this->screen_options->init( $current_screen->id );
			}
		);
	}
}
