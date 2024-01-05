<?php
/**
 * Main Footer Block
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Blocks;

/**
 * Class for Main Footer Block
 *
 * @since 1.0.0
 */
class MainFooter {

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $privacy_policy_title = null;

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $privacy_policy_url = null;

	/**
	 * Initialize the block.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->setup_privacy_policy();
	}

	/**
	 * Gets the title and url of the privacy policy page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function setup_privacy_policy(): void {
		if ( ! is_multisite() ) {
			return;
		}

		switch_to_blog( get_main_site_id() );

		$page_id = get_option( 'wp_page_for_privacy_policy' );
		$this->privacy_policy_title = get_the_title( $page_id );
		$this->privacy_policy_url   = get_privacy_policy_url( $page_id );
		restore_current_blog();
	}

	/**
	 * Returns the text for the main site
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_sitename_text(): string {
		$sitename_text = __( 'GOODBIDS Positive Auctions', 'goodbids' );

		if ( ! is_multisite() ) {
			return $sitename_text;
		}

		return sprintf(
			'%s %s',
			get_blog_option( get_main_site_id(), 'blogname' ),
			get_blog_option( get_main_site_id(), 'blogdescription' )
		);
	}

	/**
	 * Returns the Privacy Policy page title
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_privacy_policy_title(): string {
		return $this->privacy_policy_title;
	}

	/**
	 * Returns the URL for the Privacy Policy page
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_privacy_policy_url(): string {
		return $this->privacy_policy_url;
	}
}
