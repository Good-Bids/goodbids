<?php
/**
 * Multisite Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Auctions\Auction;
use GoodBids\Auctions\Auctions;
use GoodBids\Auctions\Bids;
use GoodBids\Auctions\Rewards;
use GoodBids\Utilities\Log;
use Illuminate\Support\Collection;
use WP_Block_Type_Registry;
use WP_Post;
use WP_Site;

/**
 * Network Sites Class
 *
 * @since 1.0.0
 */
class Sites {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const OPTION_SLUG = 'gbnp';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ALL_AUCTIONS_TRANSIENT = '_goodbids_all_auctions';

	/**
	 * Nonprofit custom fields
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private array $np_fields = [];

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init_np_fields();

		// Process New Site Custom Meta Fields.
		$this->new_site_form_fields();
		$this->validate_new_site_fields();
		$this->save_new_site_fields();

		// Process Edit Site Custom Meta Fields.
		$this->edit_site_form_fields();
		$this->save_edit_site_fields();

		// New Site Actions
		$this->activate_child_theme_on_new_site();
		$this->default_child_theme_logo();
		$this->set_default_posts_per_page();
		$this->disable_blocks_for_nonprofits();
		$this->create_about_page();
		$this->lock_block_editor();

		// Sites Custom Columns
		$this->customize_sites_columns();

		// Auto-register users on new sites.
		$this->auto_register_user();

		// Refresh transients when Auctions change status.
		$this->maybe_clear_transients();
	}

	/**
	 * Initialize Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_np_fields(): void {
		$this->np_fields = apply_filters(
			'goodbids_nonprofit_custom_fields',
			[
				'legal-name' => [
					'label'       => __( 'Nonprofit Legal Name', 'goodbids' ),
					'type'        => 'text',
					'default'     => '',
					'placeholder' => '',
					'required'    => true,
					'context'     => 'both',
				],
				'ein'        => [
					'label'       => __( 'Nonprofit EIN', 'goodbids' ),
					'type'        => 'text',
					'default'     => '',
					'placeholder' => 'XX-XXXXXXX',
					'required'    => true,
					'context'     => 'both',
				],
				'website'    => [
					'label'       => __( 'Nonprofit Website', 'goodbids' ),
					'type'        => 'url',
					'default'     => '',
					'placeholder' => 'https://',
					'required'    => true,
					'context'     => 'both',
				],
				'status'     => [
					'label'       => __( 'Site Status', 'goodbids' ),
					'type'        => 'select',
					'default'     => 'pending',
					'placeholder' => '',
					'required'    => true,
					'context'     => 'edit',
					'options'     => [
						[
							'label' => __( 'Pending', 'goodbids' ),
							'value' => 'pending',
						],
						[
							'label' => __( 'Published', 'goodbids' ),
							'value' => 'published',
						],
						[
							'label' => __( 'Disabled', 'goodbids' ),
							'value' => 'disabled',
						],
					],
				],
			]
		);
	}

	/**
	 * Retrieve array of Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context Context for the fields. Default 'both'.
	 *                        Accepts 'create', 'edit', or 'both'.
	 *
	 * @return array
	 */
	public function get_np_fields( string $context = 'both' ): array {
		if ( empty( $this->np_fields ) ) {
			$this->init_np_fields();
		}

		if ( 'both' === $context ) {
			return $this->np_fields;
		}

		$fields = [];

		foreach ( $this->np_fields as $key => $field ) {
			if ( empty( $field['context'] ) ) {
				continue;
			}

			if ( in_array( $field['context'], [ $context, 'both' ], true ) ) {
				$fields[ $key ] = $field;
			}
		}

		return $fields;
	}

	/**
	 * Retrieve Nonprofit custom field data.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $site_id Site ID.
	 * @param string $field_id The Field ID to retrieve. Default empty.
	 *
	 * @return mixed
	 */
	public function get_np_data( int $site_id, string $field_id = '' ): mixed {
		$data = [];

		foreach ( $this->get_np_fields() as $key => $field ) {
			$field_key   = self::OPTION_SLUG . '-' . $key;
			$field_value = get_site_meta( $site_id, $field_key, true );

			if ( ! $field_value && ! empty( $field['default'] ) ) {
				$field_value = $field['default'];
			}

			if ( $key === $field_id ) {
				return $field_value;
			}

			$data[ $key ] = $field_value;
		}

		return $data;
	}

	/**
	 * Validate required Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function validate_new_site_fields(): void {
		add_action(
			'admin_init',
			function () {
				if ( empty( $_REQUEST['action'] ) || 'add-site' !== $_REQUEST['action'] ) {
					return;
				}

				check_admin_referer( 'add-np-site', '_wpnonce_add-np-site' );

				if ( empty( $_POST[ self::OPTION_SLUG ] ) || ! is_array( $_POST[ self::OPTION_SLUG ] ) ) {
					wp_die( esc_html__( 'Missing required Nonprofit data.' ) );
				}

				// Grab our nonprofit data.
				$data = $_POST[ self::OPTION_SLUG ]; // phpcs:ignore

				// Validate required fields.
				foreach ( $this->get_np_fields( 'create' ) as $key => $field ) {
					if ( empty( $field['required'] ) || true !== $field['required'] ) {
						continue;
					}

					if ( empty( $data[ $key ] ) ) {
						wp_die(
							sprintf(
								'%s %s',
								esc_html( $field['label'] ),
								esc_html__( 'is a required field.', 'goodbids' )
							)
						);
					}
				}

				// Validate EIN as 9 digits.
				if ( ! empty( $data['ein'] ) ) {
					$ein = preg_replace( '/[^0-9]/', '', $data['ein'] );

					if ( 9 !== strlen( $ein ) ) {
						wp_die( esc_html__( 'Nonprofit EIN must include 9 digits. (##-#######)', 'goodbids' ) );
					}
				}

				// Validate Website as URL.
				if ( ! empty( $data['website'] ) ) {
					if ( ! filter_var( $data['website'], FILTER_VALIDATE_URL ) ) {
						wp_die( esc_html__( 'Nonprofit Website must be a valid URL. Be sure to include "https://".', 'goodbids' ) );
					}
				}
			}
		);
	}

	/**
	 * Add GoodBids Nonprofit fields to the new site form
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function new_site_form_fields(): void {
		add_action(
			'network_site_new_form',
			function () {
				$fields = $this->get_np_fields( 'create' );
				$prefix = self::OPTION_SLUG;

				goodbids()->load_view( 'network/new-site-fields.php', compact( 'fields', 'prefix' ) );
			}
		);
	}

	/**
	 * Add GoodBids Nonprofit fields to the edit site form
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function edit_site_form_fields(): void {
		add_action(
			'network_site_info_form',
			function ( $site_id ) {
				$data   = $this->get_np_data( $site_id );
				$fields = $this->get_np_fields( 'edit' );
				$prefix = self::OPTION_SLUG;

				goodbids()->load_view(
					'network/edit-site-fields.php',
					compact( 'data', 'fields', 'prefix' )
				);
			}
		);
	}

	/**
	 * Save nonprofit field data to database.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function save_new_site_fields(): void {
		add_action(
			'wp_initialize_site',
			/**
			 * @param WP_Site $new_site New site object.
			 */
			function ( WP_Site $new_site ) {
				if ( empty( $_POST[ self::OPTION_SLUG ] ) ) { // phpcs:ignore
					Log::error( 'Missing required Nonprofit data.', compact( 'new_site' ) );
					return;
				}

				$data = $_POST[ self::OPTION_SLUG ]; // phpcs:ignore

				foreach ( $this->get_np_fields( 'create' ) as $key => $field ) {
					if ( ! isset( $data[ $key ] ) ) {
						continue;
					}

					$meta_key   = self::OPTION_SLUG . '-' . $key;
					$meta_value = sanitize_text_field( $data[ $key ] );
					update_site_meta( $new_site->id, $meta_key, $meta_value );
				}

				$this->init_site_defaults( $new_site->id );
			}
		);
	}

	/**
	 * Update nonprofit field data to database.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function save_edit_site_fields(): void {
		add_action(
			'wp_update_site',
			/**
			 * @param WP_Site $new_site New site object.
			 */
			function ( WP_Site $new_site ): void {
				if ( wp_doing_ajax() ) {
					return;
				}

				if ( empty( $_POST[ self::OPTION_SLUG ] ) ) {
					Log::error( 'Missing required Nonprofit data.', compact( 'new_site' ) );
					return;
				}

				check_admin_referer( 'edit-np-site', '_wpnonce_edit-np-site' );

				$data = $_POST[ self::OPTION_SLUG ]; // phpcs:ignore

				foreach ( $this->get_np_fields( 'edit' ) as $key => $field ) {
					if ( ! isset( $data[ $key ] ) ) {
						continue;
					}

					$meta_key   = self::OPTION_SLUG . '-' . $key;
					$meta_value = sanitize_text_field( $data[ $key ] );
					update_site_meta( $new_site->id, $meta_key, $meta_value );
				}
			}
		);
	}

	/**
	 * Activate nonprofit child theme.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function activate_child_theme_on_new_site(): void {
		add_action(
			'goodbids_init_site',
			function () {
				$stylesheet = 'goodbids-nonprofit';

				// Check if the Goodbids child theme exists first.
				if ( wp_get_theme( $stylesheet )->exists() ) {
					switch_theme( $stylesheet );
				}
			}
		);
	}

	/**
	 * Initialize new site defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return void
	 */
	private function init_site_defaults( int $site_id ): void {
		$this->swap(
			fn() => do_action( 'goodbids_init_site', $site_id ),
			$site_id
		);
	}

	/**
	 * Set the GoodBids logo on the child theme.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function default_child_theme_logo(): void {
		add_filter(
			'get_custom_logo',
			function ( string $html ) {
				if ( $html ) {
					return $html;
				}

				if (
					$this->main(
						fn () => get_theme_mod( 'custom_logo' )
					)
				) {
					return get_custom_logo( get_main_site_id() );
				}

				return '<!-- No Custom Logo -->';
			}
		);
	}

	/**
	 * Set the archive default to show 9 posts per page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_default_posts_per_page(): void {
		add_action(
			'goodbids_init_site',
			function (): void {
				update_option(
					'posts_per_page',
					goodbids()->get_config( 'sites.default-posts-per-page' )
				);
			}
		);
	}

	/**
	 * Only allow super admins to lock and unlock blocks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function lock_block_editor(): void {
		add_filter(
			'block_editor_settings_all',
			static function ( $settings ) {
				if ( ! is_super_admin() ) {
					$settings['canLockBlocks'] = false;
				}
				return $settings;
			}
		);
	}

	/**
	 * Hide blocks on nonprofits sites
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_blocks_for_nonprofits(): void {
		add_filter(
			'allowed_block_types_all',
			function ( $allowed_block_types ) {
				if ( is_main_site() ) {
					return $allowed_block_types;
				}

				$disabled = [
					'acf/site-directory',
				];

				if ( ! is_array( $allowed_block_types ) ) {
					$allowed_block_types = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
				}

				// Remove the block from the allowed blocks.
				return array_values( array_diff( $allowed_block_types, $disabled ) );
			},
		);
	}

	/**
	 * Loop through all Nonprofit sites with a callback function
	 *
	 * @since 1.0.0
	 *
	 * @param callable|array $callback
	 * @param array          $site_args
	 *
	 * @return array
	 */
	public function loop( callable|array $callback, array $site_args = [] ): array {
		if ( ! is_callable( $callback ) ) {
			return [];
		}

		return collect( get_sites( $site_args ) )
			->flatMap(
				fn( WP_Site $site ) => $this->swap(
					fn ( int $site_id ) => call_user_func( $callback, $site_id ),
					$site->blog_id
				)
			)
			->all();
	}

	/**
	 * Swap between sites with a callback function
	 *
	 * @since 1.0.0
	 *
	 * @param callable|array $callback
	 * @param int            $site_id
	 *
	 * @return mixed
	 */
	public function swap( callable|array $callback, int $site_id ): mixed {
		if ( ! is_callable( $callback ) ) {
			return false;
		}

		if ( get_current_blog_id() === $site_id ) {
			return call_user_func( $callback, $site_id );
		}

		switch_to_blog( $site_id );
		$return = call_user_func( $callback, $site_id );
		restore_current_blog();

		return $return;
	}

	/**
	 * Swap to the main site with a callback function
	 *
	 * @since 1.0.0
	 *
	 * @param callable|array $callback
	 *
	 * @return mixed
	 */
	public function main( callable|array $callback ): mixed {
		if ( ! is_callable( $callback ) && ! is_array( $callback ) ) {
			return false;
		}

		return $this->swap( $callback, get_main_site_id() );
	}

	/**
	 * Automatically register users as a customer when they visit a new Nonprofit site.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function auto_register_user(): void {
		add_action(
			'template_redirect',
			function (): void {
				$user_id = get_current_user_id();

				if ( ! $user_id ) {
					return;
				}

				$this->add_user_to_site( $user_id );
			}
		);
	}

	/**
	 * Adds a user to a site.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $user_id
	 * @param ?int $site_id
	 *
	 * @return void
	 */
	public function add_user_to_site( int $user_id, ?int $site_id = null ): void {
		if ( null === $site_id ) {
			$site_id = get_current_blog_id();
		}

		// Check if the user is already registered on the site.
		if ( is_user_member_of_blog( $user_id, $site_id ) ) {
			return;
		}

		// Add the user to the site.
		add_user_to_blog( $site_id, $user_id, 'customer' );
	}

	/**
	 * Sets a pattern template for the page post type
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function create_about_page(): void {
		add_action(
			'goodbids_init_site',
			function (): void {
				ob_start();

				goodbids()->load_view( 'patterns/template-about-page.php' );

				$about = [
					'post_title'   => __( 'About GOODBIDS', 'goodbids' ),
					'post_content' => ob_get_clean(),
					'post_type'    => 'page',
					'post_status'  => [ 'publish' ],
					'post_author'  => 1,
					'post_name'    => 'about',
				];

				$about_id = wp_insert_post( $about );

				if ( is_numeric( $about_id ) ) { // This function can return a WP_Error object.
					// TODO: Use the $site_id to update post meta to track which $about_id is the About page.
				}
			},
			20
		);
	}

	/**
	 * Get the privacy policy link for the site.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_privacy_policy_link(): ?string {
		if ( ! is_multisite() ) {
			return null;
		}

		return $this->main(
			function (): string {
				$privacy_policy_link = '';
				$privacy_policy_id   = get_option( 'wp_page_for_privacy_policy' );

				if ( $privacy_policy_id ) {
					$privacy_policy_link = sprintf(
						'<a href="%s">%s</a>',
						get_privacy_policy_url(),
						get_the_title( $privacy_policy_id ),
					);
				}

				return $privacy_policy_link;
			}
		);
	}

	/**
	 * Get the terms and conditions link for the site.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_terms_conditions_link(): ?string {
		if ( ! is_multisite() ) {
			return null;
		}

		return $this->main(
			function (): string {
				$terms_conditions_link = '';
				$terms_conditions_id   = wc_terms_and_conditions_page_id();

				if ( $terms_conditions_id ) {
					$terms_conditions_link = sprintf(
						'<a href="%s">%s</a>',
						get_page_link( $terms_conditions_id ),
						get_the_title( $terms_conditions_id ),
					);
				}

				return $terms_conditions_link;
			}
		);
	}

	/**
	 * Returns an array of all published auctions across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args
	 *
	 * @return array
	 */
	public function get_all_auctions( array $query_args = [] ): array {
		$auctions = empty( $query_args ) ? get_transient( self::ALL_AUCTIONS_TRANSIENT ) : false;

		if ( $auctions ) {
			return $auctions;
		}

		$auctions = $this->loop(
			fn ( int $site_id ) => collect( ( goodbids()->auctions->get_all( $query_args ) )->posts )
				->map(
					fn ( int $post_id ) => [
						'post_id' => $post_id,
						'site_id' => $site_id,
					]
				)
				->sortByDesc(
					fn ( array $auction_data ) => [
						'bid_count'    => $this->swap(
							function() use ( $auction_data ) {
								$auction = goodbids()->auctions->get( $auction_data['post_id'] );
								return $auction->get_bid_count();
							},
							$auction_data['site_id']
						),
						'total_raised' => $this->swap(
							function() use ( $auction_data ) {
								$auction = goodbids()->auctions->get( $auction_data['post_id'] );
								return $auction->get_total_raised();
							},
							$auction_data['site_id']
						),
					]
				)
				->all()
		);

		if ( empty( $query_args ) ) {
			set_transient( self::ALL_AUCTIONS_TRANSIENT, $auctions, DAY_IN_SECONDS );
		}

		return $auctions;
	}

	/**
	 * Get the top 3 featured auctions from all sites.
	 * Auctions are sorted by highest bid count, then by highest total raised.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args
	 *
	 * @return array
	 */
	public function get_featured_auctions( array $query_args = [] ): array {
		return collect( $this->get_all_auctions( $query_args ) )
			->slice( 0, 3 )
			->values()
			->all();
	}

	/**
	 * Reset the Auction transients when an Auction status changes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_clear_transients(): void {
		add_action(
			'transition_post_status',
			function ( string $new_status, string $old_status, WP_Post $post ): void {
				if ( goodbids()->auctions->get_post_type() !== get_post_type( $post ) ) {
					return;
				}

				if ( $new_status === $old_status ) {
					return;
				}

				delete_transient( self::ALL_AUCTIONS_TRANSIENT );
			},
			10,
			3
		);

		add_action(
			'wp_trash_post',
			function ( int $post_id ): void {
				if ( goodbids()->auctions->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				delete_transient( self::ALL_AUCTIONS_TRANSIENT );
			}
		);
	}

	/**
	 * Returns an array of all bid orders for a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $user_id
	 * @param array  $status
	 * @param string $type
	 *
	 * @return array
	 */
	public function get_user_orders( ?int $user_id = null, array $status = [], string $type = Bids::ITEM_TYPE ): array {
		return collect(
			$this->loop(
				function ( $site_id ) use ( $type, $user_id, $status ) {
					if ( Bids::ITEM_TYPE === $type ) {
						$orders = goodbids()->woocommerce->account->get_user_bid_order_ids( $user_id, -1, $status );
					} elseif ( Rewards::ITEM_TYPE ) {
						$orders = goodbids()->woocommerce->account->get_user_reward_order_ids( $user_id, -1, $status );
					} else {
						return [];
					}

					return collect( $orders )
						->map(
							fn ( $order_id ) => [
								'order_id' => $order_id,
								'site_id'  => $site_id,
							]
						)
						->all();
				}
			)
		)
		->sortByDesc(
			function ( $rewards_order ) {
				return $this->swap(
					function () use ( $rewards_order ) {
						$order = wc_get_order( $rewards_order['order_id'] );
						return $order->get_date_created( 'edit' )->date( 'Y-m-d H:i:s' );
					},
					$rewards_order['site_id']
				);
			}
		)
		->all();
	}

	/**
	 * Returns an array of all bid orders for a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int  $user_id
	 * @param array $status
	 *
	 * @return array
	 */
	public function get_user_bid_orders( ?int $user_id = null, array $status = [] ): array {
		return $this->get_user_orders( $user_id, $status, Bids::ITEM_TYPE );
	}

	/**
	 * Get total number of successful bids for a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return int
	 */
	public function get_user_total_bids( ?int $user_id = null ): int {
		return count( $this->get_user_bid_orders( $user_id, [ 'processing', 'completed' ] ) );
	}

	/**
	 * Get total amount donated by a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return float
	 */
	public function get_user_total_donated( ?int $user_id = null ): float {
		return collect( $this->get_user_bid_orders( $user_id, [ 'processing', 'completed' ] ) )
			->sum(
				fn ( array $goodbids_order ) => $this->swap(
					function () use ( $goodbids_order ) {
						$order = wc_get_order( $goodbids_order['order_id'] );
						return $order->get_total();
					},
					$goodbids_order['site_id']
				)
			);
	}

	/**
	 * Get total number of nonprofits supported by a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return int
	 */
	public function get_user_nonprofits_supported( ?int $user_id = null ): int {
		return collect( $this->get_user_bid_orders( $user_id, [ 'processing', 'completed' ] ) )
			->groupBy( 'site_id' )
			->count();
	}

	/**
	 * Get all Auctions user has participated in.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return array
	 */
	public function get_user_participating_auctions( ?int $user_id = null ): array {
		return collect( $this->get_user_bid_orders( $user_id, [ 'processing', 'completed' ] ) )
			->map(
				function ( array $item ) {
					return $this->swap(
						function () use ( &$item ) {
							$item['auction_id'] = goodbids()->woocommerce->orders->get_auction_id( $item['order_id'] );
							return $item;
						},
						$item['site_id']
					);
				}
			)
			->groupBy( 'auction_id' )
			->map(
				fn( Collection $group ) => [
					'site_id'    => $group->first()['site_id'],
					'auction_id' => $group->first()['auction_id'],
					'count'      => $group->count(),
				]
			)
			->all();
	}

	/**
	 * Get all LiveAuctions user is participated in.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_user_live_participating_auctions(): array {
		return collect( $this->get_user_participating_auctions() )
			->filter(
				fn( array $item ) => $this->swap(
					function () use ( &$item ) {
						$auction = goodbids()->auctions->get( $item['auction_id'] );
						return Auction::STATUS_LIVE === $auction->get_status();
					},
					$item['site_id']
				)
			)
			->all();
	}

	/**
	 * Get Auctions from all Nonprofit sites won by User
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return array
	 */
	public function get_user_auctions_won( ?int $user_id = null ): array {
		return collect( $this->get_user_participating_auctions( $user_id ) )
			->filter(
				function ( $auction ) {
					return $this->swap(
						fn () => goodbids()->auctions->is_current_user_winner( $auction['auction_id'] ),
						$auction['site_id']
					);
				}
			)
			->all();
	}

	/**
	 * Returns an array of all reward orders for a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int  $user_id
	 * @param array $status
	 *
	 * @return array
	 */
	public function get_user_reward_orders( ?int $user_id = null, array $status = [] ): array {
		return $this->get_user_orders( $user_id, $status, Rewards::ITEM_TYPE );
	}

	/**
	 * Display custom content on Network Sites page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function customize_sites_columns(): void {
		add_filter(
			'wpmu_blogs_columns',
			function ( $columns ) {
				$columns['standing'] = __( 'Account Standing', 'goodbids' );
				return $columns;
			}
		);

		add_action(
			'manage_sites_custom_column',
			function ( string $column, string $site_id ) {
				if ( 'standing' === $column ) {
					if ( get_main_site_id() !== intval( $site_id ) ) {
						goodbids()->sites->swap(
							function() {
								if ( goodbids()->invoices->has_overdue_invoices() ) {
									esc_html_e( 'Delinquent', 'goodbids' );
									return;
								}

								esc_html_e( 'Good', 'goodbids' );
							},
							intval( $site_id )
						);
					} else {
						esc_html_e( 'N/A', 'goodbids' );
					}
				}
			},
			10,
			2
		);
	}
}
