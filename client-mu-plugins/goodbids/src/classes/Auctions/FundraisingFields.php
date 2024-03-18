<?php
/**
 * Auction Fundraising Fields
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

/**
 * Fundraising Fields Class
 *
 * @since 1.0.0
 */
class FundraisingFields {

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Removes some ACF fields from the Admin UI.
		$this->disable_fundraising_fields();

		// Temporarily disable the Reward Tab.
		$this->maybe_disable_reward_tab();
	}

	/**
	 * Temporarily Disable some of the Fundraising Auction fields
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_fundraising_fields(): void {
		add_action(
			'current_screen',
			function(): void {
				if ( is_super_admin() ) {
					return;
				}

				$screen = get_current_screen();
				if ( goodbids()->auctions->get_post_type() !== $screen->id ) {
					return;
				}

				?>
				<style>
					div.acf-field[data-name="estimated_value"],
					div.acf-field[data-name="auction_goal"],
					div.acf-field[data-name="expected_high_bid"] {
						display: none;
					}
					div.acf-field[data-name="auction_product"] {
						width: 100% !important;
					}
				</style>
				<?php
			}
		);
	}

	/**
	 * Temporarily Disable the Reward Tab if the Auction is published
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_disable_reward_tab(): void {
		add_action(
			'current_screen',
			function(): void {
				if ( ! goodbids()->auctions->should_hide_reward_product() ) {
					return;
				}

				?>
				<style>
					.acf-tab-group li:has(a[data-key="field_65859ba03eb29"]) {
						display: none !important;
					}
				</style>
				<?php
			}
		);
	}
}
