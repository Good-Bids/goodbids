<?php
/**
 * GoodBids Nonprofit Verification
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

/**
 * Verification Class
 *
 * @since 1.0.0
 */
class Verification {

	/**
	 * Verification Page Slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'goodbids-verification';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Add Verification Submenu
		$this->add_submenu_page();

		// Add Verification Site Action
		$this->add_site_action();
	}

	/**
	 * Add a Submenu item under Sites for the Verification Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_submenu_page(): void {
		add_action(
			'network_admin_menu',
			function (): void {
				// Only show the page if the user is on the Verification Page.
				if ( empty( $_GET['page'] ) || self::PAGE_SLUG !== sanitize_text_field( wp_unslash( $_GET['page'] ) ) || empty( $_GET['site_id'] ) ) {
					return;
				}

				add_submenu_page(
					'sites.php',
					esc_html__( 'Verification', 'goodbids' ),
					esc_html__( 'Verification', 'goodbids' ),
					'manage_sites',
					self::PAGE_SLUG,
					[ $this, 'verification_page' ]
				);
			}
		);
	}

	/**
	 * Render the Verification Admin Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function verification_page(): void {
		echo 'Verification Page.';
	}

	/**
	 * Add Verification to the Sites List
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_site_action(): void {
		add_filter(
			'manage_sites_action_links',
			function ( array $actions, int $blog_id ): array {
				if ( get_main_site_id() === $blog_id ) {
					return $actions;
				}

				$url = add_query_arg( 'page', self::PAGE_SLUG, network_admin_url( 'sites.php' ) );
				$url = add_query_arg( 'site_id', $blog_id, $url );

				$actions['verification'] = sprintf(
					'<a href="%s">%s</a>',
					esc_url( $url ),
					__( 'Verification', 'goodbids' )
				);

				return $actions;
			},
			10,
			2
		);
	}
}
