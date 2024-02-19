<?php
/**
 * Patterns Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

use GoodBids\Utilities\Log;

/**
 * Class for patterns
 *
 * @since 1.0.0
 */
class Patterns {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const DEFAULT_NAMESPACE = 'goodbids';

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $patterns = [];

	/**
	 * Initialize Patterns
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->register_patterns();
	}

	/**
	 * Register Block Patterns
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_patterns(): void {
		add_action(
			'init',
			function (): void {
				// Register the GoodBids Pattern Category.
				register_block_pattern_category(
					self::DEFAULT_NAMESPACE,
					[
						'label' => __( 'GoodBids', 'goodbids' ),
					]
				);

				$auction_archive = [
					'name'       => 'template-archive-auction',
					'path'       => goodbids()->get_view_path( 'patterns/template-archive-auction.php' ),
					'title'      => __( 'Archive Auction', 'goodbids' ),
					'categories' => [ 'goodbids' ],
					'keywords'   => [ 'non-profit', 'starter', 'archive' ],
					'inserter'   => true,
				];

				$comments = [
					'name'       => 'goodbids-comments',
					'path'       => goodbids()->get_view_path( 'patterns/comments.php' ),
					'title'      => __( 'Goodbids Comments', 'goodbids' ),
					'categories' => [ 'page', 'goodbids' ],
					'keywords'   => [ 'comments', 'auction', 'page', 'goodbids' ],
					'source'     => 'plugin',
					'inserter'   => true,
				];

				$hero_banner = [
					'name'       => 'goodbids-banner-hero',
					'path'       => goodbids()->get_view_path( 'patterns/banner-hero.php' ),
					'title'      => __( 'Goodbids Banner Hero', 'goodbids' ),
					'categories' => [ 'banner', 'featured', 'call-to-action', 'goodbids' ],
					'keywords'   => [ 'banner', 'call-to-action', 'featured', 'hero' ],
					'source'     => 'plugin',
					'inserter'   => true,
				];

				$logo_grid = [
					'name'        => 'logo-grid',
					'path'        => goodbids()->get_view_path( 'patterns/logo-grid.php' ),
					'title'       => __( 'Logo Grid', 'goodbids' ),
					'description' => _x( 'Grid of image for logos', 'Block pattern description', 'goodbids' ),
					'categories'  => [ 'page', 'goodbids', 'gallery' ],
					'keywords'    => [ 'image', 'logo', 'grid' ],
					'source'      => 'plugin',
					'inserter'    => true,
				];

				$nonprofit_interest_form = [
					'name'        => 'nonprofit-interest-form',
					'path'        => goodbids()->get_view_path( 'patterns/nonprofit-interest-form.php' ),
					'title'       => __( 'Nonprofit Interest Form', 'goodbids' ),
					'description' => _x( 'Nonprofit Interest Form using Jetpack forms', 'Block pattern description', 'goodbids' ),
					'categories'  => [ 'goodbids' ],
					'keywords'    => [ 'non-profit', 'form' ],
					'source'      => 'plugin',
					'inserter'    => true,
				];

				$section_sidebar_chapters = [
					'name'        => 'section-sidebar-chapters',
					'path'        => goodbids()->get_view_path( 'patterns/section-sidebar-chapters.php' ),
					'title'       => __( 'Section with Sidebar and Chapters', 'goodbids' ),
					'description' => _x( 'Template for any page with sidebar and chapters', 'Block pattern description', 'goodbids' ),
					'categories'  => [ 'page', 'goodbids' ],
					'keywords'    => [ 'conditions', 'terms', 'page', 'sidebar' ],
					'postTypes'   => [ 'page' ],
					'source'      => 'plugin',
					'inserter'    => true,
				];

				$template_about = [
					'name'          => 'template-about-page',
					'path'          => goodbids()->get_view_path( 'patterns/template-about-page.php' ),
					'title'         => __( 'About GoodBids', 'goodbids' ),
					'description'   => _x( 'Template for About Page', 'Block pattern description', 'goodbids' ),
					'categories'    => [ 'about','page', 'goodbids' ],
					'keywords'      => [ 'non-profit', 'starter', 'page' ],
					'blockTypes'    => [ 'core/post-content', 'core/group', 'core/paragraph' ],
					'postTypes'     => [ 'page', 'wp_template' ],
					'templateTypes' => [ 'front-page', 'home', 'page' ],
					'source'        => 'plugin',
					'inserter'      => true,
				];

				$template_auction = [
					'name'        => 'template-auction',
					'path'        => goodbids()->get_view_path( 'patterns/template-auction.php' ),
					'title'       => __( 'Auction', 'goodbids' ),
					'description' => _x( 'Template for GoodBids Auction Page', 'Block pattern description', 'goodbids' ),
					'categories'  => [ 'page', 'goodbids' ],
					'keywords'    => [ 'non-profit', 'auction' ],
					'postTypes'   => [ 'gb-auction', 'wp_template' ],
					'source'      => 'plugin',
					'inserter'    => false,
				];

				$template_terms_conditions = [
					'name'          => 'template-terms-conditions',
					'path'          => goodbids()->get_view_path( 'patterns/template-terms-conditions.php' ),
					'title'         => __( 'Terms and Conditions', 'goodbids' ),
					'description'   => _x( 'Template for GoodBids Terms and Conditions Page', 'Block pattern description', 'goodbids' ),
					'categories'    => [ 'page', 'goodbids' ],
					'keywords'      => [ 'conditions', 'terms', 'page', 'sidebar' ],
					'postTypes'     => [ 'page', 'wp_template' ],
					'templateTypes' => [ 'page' ],
					'source'        => 'plugin',
					'inserter'      => true,
				];

				$this->patterns = apply_filters(
					'goodbids_block_patterns',
					[
						$auction_archive,
						$comments,
						$hero_banner,
						$logo_grid,
						$nonprofit_interest_form,
						$section_sidebar_chapters,
						$template_about,
						$template_auction,
						$template_terms_conditions,
					]
				);

				foreach ( $this->patterns as $pattern ) {
					$this->register_pattern( $pattern );
				}
			}
		);
	}

	/**
	 * Register a block pattern
	 *
	 * @since 1.0.0
	 *
	 * @param array $pattern
	 *
	 * @return bool
	 */
	public function register_pattern( array $pattern ): bool {
		if ( ! file_exists( $pattern['path'] ) ) {
			return false;
		}

		$path      = $pattern['path'];
		$name      = $pattern['name'];
		$extension = pathinfo( $path, PATHINFO_EXTENSION );

		unset( $pattern['path'], $pattern['name'] );

		if ( str_starts_with( $path, 'http' ) ) {
			Log::warning( 'Block pattern path is a URL and not a file path.', $pattern );
			return false;
		}

		if ( empty( $pattern['categories'] ) ) {
			$pattern['categories'] = [ self::DEFAULT_NAMESPACE ];
		}

		if ( 'php' === $extension ) {
			ob_start();
			include $path;
			$content = ob_get_clean();
		} else {
			$content = file_get_contents( $path ); // phpcs:ignore
		}

		$pattern['content'] = $content;

		if ( ! str_contains( $name, '/' ) ) {
			$name = self::DEFAULT_NAMESPACE . '/' . $name;
		}

		return register_block_pattern( $name, $pattern );
	}
}
