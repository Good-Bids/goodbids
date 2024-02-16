<?php
/**
 * Referrals Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users;

use GoodBids\Auctions\FreeBid;
use GoodBids\Core;
use GoodBids\Users\Referrals\Admin;
use GoodBids\Users\Referrals\Generator;
use GoodBids\Users\Referrals\Referral;
use GoodBids\Users\Referrals\Shortcodes;
use GoodBids\Users\Referrals\Track;
use GoodBids\Utilities\Log;
use WP_Query;

/**
 * Class for User Referrals
 *
 * @since 1.0.0
 */
class Referrals {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const POST_TYPE = 'gb-referral';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const USER_ID_META_KEY = '_user_id';

	/**
	 * @since 1.0.0
	 */
	const REFERRER_ID_META_KEY = '_referrer_id';

	/**
	 * @since 1.0.0
	 */
	const REFERRAL_CODE_META_KEY = '_referral_code';

	/**
	 * @since 1.0.0
	 */
	const CONVERTED_DATE_META_KEY = '_converted_date';

	/**
	 * @since 1.0.0
	 * @var ?Generator
	 */
	public ?Generator $generator = null;

	/**
	 * Initialize User Referrals
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize Submodules
		$this->generator = new Generator();

		// Initialize Admin
		new Admin();

		// Initialize Shortcodes
		new Shortcodes();

		// Disable Referrals on Main Site.
		if ( is_main_site() && ! Core::is_dev_env() ) {
			return;
		}

		// Register Post Type.
		$this->register_post_type();

		// Add custom Admin Columns for Referrals.
		$this->add_admin_columns();

		// Initialize Tracking
		new Track();
	}

	/**
	 * Register the Referrals post type
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_post_type(): void {
		add_action(
			'init',
			function () {
				$labels = [
					'name'                  => _x( 'Referrals', 'Post Type General Name', 'goodbids' ),
					'singular_name'         => _x( 'Referral', 'Post Type Singular Name', 'goodbids' ),
					'menu_name'             => __( 'Referrals', 'goodbids' ),
					'name_admin_bar'        => __( 'Referral', 'goodbids' ),
					'archives'              => __( 'Referral Archives', 'goodbids' ),
					'attributes'            => __( 'Referral Attributes', 'goodbids' ),
					'parent_item_colon'     => __( 'Parent Referral:', 'goodbids' ),
					'all_items'             => __( 'Referrals', 'goodbids' ),
					'add_new_item'          => __( 'Add New Referral', 'goodbids' ),
					'add_new'               => __( 'Add New', 'goodbids' ),
					'new_item'              => __( 'New Referral', 'goodbids' ),
					'edit_item'             => __( 'Edit Referral', 'goodbids' ),
					'update_item'           => __( 'Update Referral', 'goodbids' ),
					'view_item'             => __( 'View Referral', 'goodbids' ),
					'view_items'            => __( 'View Referrals', 'goodbids' ),
					'search_items'          => __( 'Search Referrals', 'goodbids' ),
					'not_found'             => __( 'Not found', 'goodbids' ),
					'not_found_in_trash'    => __( 'Not found in Trash', 'goodbids' ),
					'featured_image'        => __( 'Featured Image', 'goodbids' ),
					'set_featured_image'    => __( 'Set featured image', 'goodbids' ),
					'remove_featured_image' => __( 'Remove featured image', 'goodbids' ),
					'use_featured_image'    => __( 'Use as featured image', 'goodbids' ),
					'insert_into_item'      => __( 'Insert into referral', 'goodbids' ),
					'uploaded_to_this_item' => __( 'Uploaded to this referral', 'goodbids' ),
					'items_list'            => __( 'Referrals list', 'goodbids' ),
					'items_list_navigation' => __( 'Referrals list navigation', 'goodbids' ),
					'filter_items_list'     => __( 'Filter referrals list', 'goodbids' ),
				];

				$args = [
					'label'               => __( 'Referral', 'goodbids' ),
					'description'         => __( 'GoodBids Referral Custom Post Type', 'goodbids' ),
					'labels'              => $labels,
					'supports'            => array( 'title' ),
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => 'edit.php?post_type=' . goodbids()->auctions->get_post_type(),
					'menu_position'       => 15,
					'menu_icon'           => 'dashicons-share-alt',
					'show_in_admin_bar'   => false,
					'show_in_nav_menus'   => false,
					'can_export'          => true,
					'has_archive'         => false,
					'exclude_from_search' => true,
					'publicly_queryable'  => false,
					'rewrite'             => false,
					'capability_type'     => 'page',
					'show_in_rest'        => true,
				];

				register_post_type( $this->get_post_type(), $args );
			}
		);
	}

	/**
	 * Insert custom admin columns
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_admin_columns(): void {
		add_filter(
			'manage_' . $this->get_post_type() . '_posts_columns',
			function ( array $columns ): array {
				$new_columns = [];

				foreach ( $columns as $column => $label ) {
					$new_columns[ $column ] = $label;

					// Insert Custom Columns after the Title column.
					if ( 'title' === $column ) {
						$new_columns['user']     = __( 'User', 'goodbids' );
						$new_columns['referrer'] = __( 'Referrer', 'goodbids' );
					}
				}

				return $new_columns;
			}
		);

		add_action(
			'manage_' . $this->get_post_type() . '_posts_custom_column',
			function ( string $column, int $post_id ): void {
				$output_user_col = function ( int $user_id ): void {
					if ( ! $user_id ) {
						echo '&mdash;';
						return;
					}

					$user = get_user_by( 'ID', $user_id );

					if ( $user ) {
						printf(
							'<a href="%s">%s</a>',
							esc_url( get_edit_user_link( $user_id ) ),
							esc_html( $user->display_name )
						);
					} else {
						echo esc_html( $user_id );
					}
				};

				// Output the column values.
				if ( 'user' === $column ) {
					$user_id = get_post_meta( $post_id, self::USER_ID_META_KEY, true );
					$output_user_col( $user_id );
				} elseif ( 'referrer' === $column ) {
					$referrer_id = get_post_meta( $post_id, self::REFERRER_ID_META_KEY, true );
					$output_user_col( $referrer_id );
				}
			},
			10,
			2
		);
	}

	/**
	 * Returns the Referral post type slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return self::POST_TYPE;
	}

	/**
	 * Search users for a referral code.
	 *
	 * @param string $code Referral code.
	 *
	 * @return int|false
	 */
	public function get_user_id_by_referral_code( string $code ): int|false {
		$user = get_users(
			array(
				'meta_key'    => Referrals::REFERRAL_CODE_META_KEY,
				'meta_value'  => trim( strtolower( $code ) ),
				'fields'      => ['ID'],
				'number'      => 1,
				'count_total' => false,
			)
		);

		return ! empty( $user ) ? intval( $user[0]->ID ) : false;
	}

	/**
	 * Get the referring user ID for the given user ID.
	 *
	 * @param ?int $user_id
	 *
	 * @return int|false
	 */
	public function get_referrer_id( ?int $user_id = null ): int|false {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		$referrer_id = get_user_meta( $user_id, self::REFERRER_ID_META_KEY, true );

		if ( ! $referrer_id ) {
			return false;
		}

		return $referrer_id;
	}

	/**
	 * Get the Referral for a given User/Referrer
	 *
	 * @since 1.0.0
	 *
	 * @param int $referrer_id
	 * @param int $user_id
	 *
	 * @return ?int
	 */
	public function get_referral( int $referrer_id, int $user_id ): ?int {
		$referral = new WP_Query(
			[
				'post_type'      => $this->get_post_type(),
				'posts_per_page' => 1,
				'post_status'    => [ 'publish' ],
				'fields'         => 'ids',
				'meta_query'     => [
					[
						'key'   => self::REFERRER_ID_META_KEY,
						'value' => $referrer_id,
					],
					[
						'key'   => self::USER_ID_META_KEY,
						'value' => $user_id,
					],
				],
			]
		);

		if ( ! $referral->have_posts() ) {
			return null;
		}

		return intval( $referral->posts[0] );
	}

	/**
	 * Track when a user has registered with a Referral Code
	 *
	 * @since 1.0.0
	 *
	 * @param int $referrer_id
	 * @param int $user_id
	 * @param ?string $code
	 *
	 * @return bool
	 */
	public function add_referral( int $referrer_id, int $user_id, string $code = null ): bool {
		$referral = new Referral();

		$referral->set_referrer_id( $referrer_id );
		$referral->set_user_id( $user_id );
		$referral->set_code( $code );

		return $referral->save();
	}

	/**
	 * Convert a Referral and award a Free Bid
	 *
	 * @since 1.0.0
	 *
	 * @param int $referrer_id
	 * @param int $user_id
	 * @param int $auction_id
	 * @param int $order_id
	 *
	 * @return bool
	 */
	public function convert( int $referrer_id, int $user_id, int $auction_id, int $order_id ): bool {
		$referral_id = $this->get_referral( $referrer_id, $user_id );

		if ( is_null( $referral_id ) ) {
			Log::warning( 'Referral not found', compact( 'user_id', 'referrer_id' ) );
			return false;
		}

		$referral = new Referral( $referral_id );

		if ( $referral->is_converted() ) {
			Log::warning( 'Referral already converted', compact( 'user_id', 'referrer_id' ) );
			return false;
		}

		$referral->convert( $auction_id, $order_id );

		$details = sprintf(
			/* translators: %1$d: User ID, %2$d: Auction ID */
			__( 'Referral converted for User ID: %1$d for placing a paid bid on Auction ID: %2$d', 'goodbids' ),
			$user_id,
			$auction_id
		);

		goodbids()->users->award_free_bid( $referrer_id, $auction_id, FreeBid::TYPE_REFERRAL, $details );

		return true;
	}

	/**
	 * Delete a referral
	 *
	 * @since 1.0.0
	 *
	 * @param int $referrer_id
	 * @param int $user_id
	 *
	 * @return bool|int
	 */
	public function delete( int $referrer_id, int $user_id ): bool|int {
		$referral_id = $this->get_referral( $referrer_id, $user_id );

		if ( is_null( $referral_id ) ) {
			Log::warning( 'No Referral found', compact( 'user_id', 'referrer_id' ) );
			return false;
		}

		$deleted = wp_delete_post( $referral_id, true );

		if ( ! $deleted ) {
			Log::error( 'There was a problem deleting a Referral', compact( 'user_id', 'referrer_id', 'referral_id' ) );
			return false;
		}

		return true;
	}

	/**
	 * Set the referrer for a user
	 *
	 * @param int $user_id
	 * @param int $referrer_id
	 *
	 * @return bool|int
	 */
	public function set_referrer( int $user_id, int $referrer_id ): bool|int {
		return update_user_meta( $user_id, Referrals::REFERRER_ID_META_KEY, $referrer_id );
	}

	/**
	 * Get Top Referrers
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_top_referrers(): array {
		global $wpdb;
		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT COUNT(meta_value) as counted, meta_value as id FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value IS NOT NULL GROUP BY meta_value ORDER BY counted DESC LIMIT %d",
				Referrals::REFERRER_ID_META_KEY,
				10
			),
			ARRAY_A
		);
	}
}
