<?php
/**
 * GoodBids Network Nonprofits
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

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
	 * @var array
	 */
	private array $nonprofits = [];

	/**
	 * @since 1.0.0
	 */
	public function __construct() {}

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
	 * Get Nonprofit Site Name
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return string
	 */
	public function get_name( int $site_id ): string {
		return goodbids()->sites->swap(
			function () {
				return get_bloginfo( 'name' );
			},
			$site_id
		);
	}

	/**
	 * Get Nonprofit Site Standing
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return string
	 */
	public function get_standing( int $site_id ): string {
		return goodbids()->sites->swap(
			function () {
				if ( goodbids()->invoices->has_overdue_invoices() ) {
					return __( 'Delinquent', 'goodbids' );
				}

				return __( 'Good', 'goodbids' );
			},
			$site_id
		);
	}

	/**
	 * Get Nonprofit Total Auctions
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return int
	 */
	public function get_total_auctions( int $site_id ): int {
		return goodbids()->sites->swap(
			fn () => goodbids()->auctions->get_all()->found_posts,
			$site_id
		);
	}

	/**
	 * Get Nonprofit Total Raised
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return float
	 */
	public function get_total_raised( int $site_id ): float {
		return goodbids()->sites->swap(
			function () {
				return 0;
			},
			$site_id
		);
	}

	/**
	 * Get Nonprofit Site Revenue
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return float
	 */
	public function get_total_revenue( int $site_id ): float {
		return goodbids()->sites->swap(
			function () {
				return 0;
			},
			$site_id
		);
	}

	/**
	 * Get Nonprofit Site Admin URL
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return string
	 */
	public function get_admin_url( int $site_id ): string {
		return goodbids()->sites->swap(
			function () {
				return admin_url();
			},
			$site_id
		);
	}
}
