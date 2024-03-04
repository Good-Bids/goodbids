<?php
/**
 * GoodBids Network Nonprofits
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Admin\ScreenOptions;

/**
 * Network Admin Nonprofits Class
 *
 * @since 1.0.0
 */
class Nonprofits {

	/**
	 * Nonprofits Page Slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-nonprofits';

	/**
	 * @since 1.0.0
	 * @var ?ScreenOptions
	 */
	private ?ScreenOptions $screen_options = null;

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $nonprofits = [];

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
						'nonprofits_table_limit' => [
							'label'   => esc_html__( 'Nonprofits Per Page', 'goodbids' ),
							'type'    => 'number',
							'class'   => 'small-text',
							'default' => 20,
						],
					]
				);

				add_filter(
					'goodbids_nonprofits_table_per_page',
					fn () => $this->screen_options->get_option( 'nonprofits_table_limit' )
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

	/**
	 * Get all Nonprofit Sites
	 *
	 * @since 1.0.0
	 *
	 * @return int[]
	 */
	public function get_all_nonprofits(): array {
		if ( ! empty( $this->nonprofits ) ) {
			return $this->nonprofits;
		}

		goodbids()->sites->loop(
			function( $site_id ) {
				if ( is_main_site() ) {
					return;
				}

				$this->nonprofits[] = $site_id;
			}
		);

		return $this->nonprofits;
	}

	/**
	 * Check if onboarding is completed.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $site_id
	 *
	 * @return bool
	 */
	public function is_onboarded( ?int $site_id = null ): bool {
		if ( ! $site_id ) {
			$site_id = get_current_blog_id();
		}

		return goodbids()->sites->swap(
			fn () => boolval( get_option( 'goodbids_onboarded' ) ),
			$site_id
		);
	}
}
