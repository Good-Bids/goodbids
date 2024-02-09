<?php
/**
 * GoodBids Network Invoices
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

/**
 * Network Admin Invoices Class
 *
 * @since 1.0.0
 */
class Invoices {

	/**
	 * Invoices Page Slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-invoices';

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $invoices = [];

	/**
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Get Invoices from all Nonprofit Sites
	 *
	 * @since 1.0.0
	 *
	 * @return array[]
	 */
	public function get_all_invoices(): array {
		if ( ! empty( $this->invoices ) ) {
			return $this->invoices;
		}

		goodbids()->sites->loop(
			function( $site_id ) {
				if ( is_main_site() ) {
					return;
				}

				$site_invoices = goodbids()->invoices->get_all_ids();

				while ( $site_invoices->have_posts() ) {
					$site_invoices->the_post();
					$this->invoices[] = [
						'post_id' => get_the_ID(),
						'site_id' => $site_id,
					];
				}

				wp_reset_postdata();
			}
		);

		return $this->invoices;
	}
}
