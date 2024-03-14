<?php
/**
 * Auctions Cron Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use DateInterval;
use GoodBids\Core;
use GoodBids\Utilities\Log;
use WP_Query;

/**
 * Class for Auction Cron
 *
 * @since 1.0.0
 */
class Cron {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const CRON_AUCTION_START_HOOK = 'goodbids_auction_start_event';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const CRON_AUCTION_CLOSE_HOOK = 'goodbids_auction_close_event';

	/**
	 * @since 1.0.0
	 * @var array
	 */
	public array $cron_intervals = [];

	/**
	 * Initialize Cron for Auctions
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Disable Auctions on Main Site.
		if ( is_main_site() && ! Core::is_local_env() ) {
			return;
		}

		// Set up Cron Schedules.
		$this->cron_intervals['1min']  = [
			'interval' => MINUTE_IN_SECONDS,
			'name'     => '1min',
			'display'  => __( 'Once Every Minute', 'goodbids' ),
		];
		$this->cron_intervals['30sec'] = [
			'interval' => 30,
			'name'     => '30sec',
			'display'  => __( 'Every 30 Seconds', 'goodbids' ),
		];
		$this->cron_intervals['daily'] = [
			'interval' => DAY_IN_SECONDS,
			'name'     => 'Daily',
			'display'  => __( 'Every Day', 'goodbids' ),
		];

		// Attempt to trigger events for opened/closed auctions.
		$this->maybe_trigger_events();

		// Set up 1min Cron Job Schedule.
		$this->add_one_min_cron_schedule();

		// Schedule a cron job to trigger the start of auctions.
		$this->schedule_auction_start_cron();

		// Schedule a cron job to trigger the close of auctions.
		$this->schedule_auction_close_cron();

		// Schedule a cron job to remind users to claim their rewards.
		$this->schedule_reward_claim_reminder();

		// Use cron action to start auctions.
		$this->cron_check_for_starting_auctions();

		// Use cron action to close auctions.
		$this->cron_check_for_closing_auctions();
	}

	/**
	 * Add a 1min Cron Job Schedule.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_one_min_cron_schedule(): void {
		add_filter(
			'cron_schedules', // phpcs:ignore
			function ( array $schedules ): array {
				foreach ( $this->cron_intervals as $id => $props ) {
					// If one is already set, confirm it matches our schedule.
					if ( ! empty( $schedules[ $props['name'] ] ) ) {
						if ( MINUTE_IN_SECONDS === $schedules[ $props['name'] ] ) {
							continue;
						}

						$this->cron_intervals[ $id ]['name'] .= '_goodbids';
					}

					// Adds every minute cron schedule.
					$schedules[ $this->cron_intervals[ $id ]['name'] ] = [
						'interval' => $this->cron_intervals[ $id ]['interval'],
						'display'  => $this->cron_intervals[ $id ]['display'],
					];
				}

				return $schedules;
			}
		);
	}

	/**
	 * Schedule a cron job that runs every minute to trigger auctions to start.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function schedule_auction_start_cron(): void {
		add_action(
			'init',
			function (): void {
				if ( wp_next_scheduled( self::CRON_AUCTION_START_HOOK ) ) {
					return;
				}

				wp_schedule_event(
					strtotime( current_time( 'mysql' ) ),
					$this->cron_intervals['1min']['name'],
					self::CRON_AUCTION_START_HOOK
				);
			}
		);
	}

	/**
	 * Schedule a cron job that runs every half minute to trigger auctions to close.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function schedule_auction_close_cron(): void {
		add_action(
			'init',
			function (): void {
				if ( wp_next_scheduled( self::CRON_AUCTION_CLOSE_HOOK ) ) {
					return;
				}

				wp_schedule_event(
					strtotime( current_time( 'mysql' ) ),
					$this->cron_intervals['30sec']['name'],
					self::CRON_AUCTION_CLOSE_HOOK
				);
			}
		);
	}

	/**
	 * Schedule a cron job that runs every day to trigger unclaimed rewards reminder
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function schedule_reward_claim_reminder(): void {
		add_action(
			'init',
			function (): void {
				if ( wp_next_scheduled( Rewards::CRON_UNCLAIMED_REMINDER_HOOK ) ) {
					return;
				}

				wp_schedule_event(
					strtotime( current_time( 'mysql' ) ),
					$this->cron_intervals['daily']['name'],
					Rewards::CRON_UNCLAIMED_REMINDER_HOOK
				);
			}
		);
	}

	/**
	 * Check for Auctions that are starting during cron hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function cron_check_for_starting_auctions(): void {
		add_action(
			self::CRON_AUCTION_START_HOOK,
			function (): void {
				$auctions = $this->get_starting_auctions();
				$starts   = [];
				$fails    = [];
				$skips    = [];

				if ( ! $auctions->have_posts() ) {
					return;
				}

				foreach ( $auctions->posts as $auction_id ) {
					$auction = goodbids()->auctions->get( $auction_id );

					// Skip START Action on Auctions that have ended.
					if ( $auction->has_ended() ) {
						$skips[] = $auction->get_id();
						continue;
					}

					if ( $auction->trigger_start() ) {
						$starts[] = $auction->get_id();
					} else {
						$fails[] = $auction->get_id();
					}
				}

				$count = count( $auctions->posts );

				if ( $count !== count( $starts ) ) {
					Log::warning(
						'Not all Auctions were started',
						[
							'expected' => $count,
							'starts'   => $starts,
							'fails'    => $fails,
							'skips'    => $skips,
							'posts'    => $auctions->posts,
						]
					);
				}
			}
		);
	}

	/**
	 * Check for Auctions that have closed during cron hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function cron_check_for_closing_auctions(): void {
		add_action(
			self::CRON_AUCTION_CLOSE_HOOK,
			function (): void {
				$auctions = $this->get_closing_auctions();

				if ( ! $auctions->have_posts() ) {
					return;
				}

				foreach ( $auctions->posts as $auction_id ) {
					$auction = goodbids()->auctions->get( $auction_id );
					$auction->trigger_close();
				}
			}
		);
	}

	/**
	 * Get Auctions that are starting.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Query
	 */
	private function get_starting_auctions(): WP_Query {
		// Use the current time + 1min to get Auctions about to start.
		$auction_start = current_datetime()->add( new DateInterval( 'PT1M' ) )->format( 'Y-m-d H:i:s' );
		$query_args    = [
			'meta_query' => [
				[
					'key'     => 'auction_start',
					'value'   => $auction_start,
					'compare' => '<=',
				],
				[
					'key'   => Auctions::AUCTION_STARTED_META_KEY,
					'value' => 0,
				],
			],
		];

		return goodbids()->auctions->get_all( $query_args );
	}

	/**
	 * Get Auctions that are closing.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Query
	 */
	private function get_closing_auctions(): WP_Query {
		$current_time = current_datetime()->format( 'Y-m-d H:i:s' );
		$query_args   = [
			'meta_query' => [
				[
					'key'     => Auctions::AUCTION_CLOSE_META_KEY,
					'value'   => $current_time,
					'compare' => '<=',
				],
				[
					'key'   => Auctions::AUCTION_CLOSED_META_KEY,
					'value' => 0,
				],
			],
		];

		return goodbids()->auctions->get_all( $query_args );
	}

	/**
	 * Additional attempt to trigger Auction events.
	 * Useful when Cron Jobs are slow or not firing.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_trigger_events(): void {
		add_action(
			'template_redirect',
			function (): void {
				$auction = goodbids()->auctions->get();

				if ( ! $auction->is_valid() ) {
					return;
				}

				// TODO: Move to background process.

				if ( $auction->has_started() ) {
					Log::debug( 'Triggering Start' );
					$auction->trigger_start();
				}

				if ( $auction->has_ended() ) {
					Log::debug( 'Triggering close' );
					$auction->trigger_close();
				}
			}
		);
	}

}
