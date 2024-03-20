<?php
/**
 * Referral Admin Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users\Referrals;

use GoodBids\Users\Referrals;
use WP_User;
use WP_User_Query;

/**
 * Class for Referral Admin
 *
 * @since 1.0.0
 */
class Admin {

	/**
	 * @since 1.0.0
	 */
	const UPDATE_CODE_NONCE = 'goodbids_update_referral_code_nonce';

	/**
	 * @since 1.0.0
	 */
	const UPDATE_CODE_NONCE_ACTION = 'update_referral_code';

	/**
	 * Initialize Referrals Admin
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'init' ] );
	}

	/**
	 * Admin init
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init(): void {
		// Customize User edit screen.
		$this->user_profile_fields();

		// Save User Profile changes.
		$this->update_user_profile();

		// Add custom columns to Users table.
		$this->customize_user_columns();

		// User AJAX Actions.
		$this->user_ajax_actions();

		// Set up JS Vars.
		$this->user_js_vars();
	}

	/**
	 * Customize User Profile Fields
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function user_profile_fields(): void {
		$profile_fields = function ( WP_User $user ): void {
			if ( ! current_user_can( 'promote_users' ) || ! current_user_can( 'edit_user', $user->ID ) ) {
				return;
			}

			$user_id  = $user->ID;
			$referrer = new Referrer( $user_id );
			$data     = compact( 'user_id', 'referrer' );

			goodbids()->load_view( 'admin/referrals/referrals.php', $data );
			goodbids()->load_view( 'admin/referrals/update-referral-code.php', $data );
		};

		add_action( 'show_user_profile', $profile_fields, 1 );
		add_action( 'edit_user_profile', $profile_fields, 1 );
	}

	/**
	 * Update User Profile
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function update_user_profile(): void {
		$get_code = function(): false|string {
			if ( ! isset( $_POST[ self::UPDATE_CODE_NONCE ] ) || ! wp_verify_nonce( sanitize_text_field( $_POST[ self::UPDATE_CODE_NONCE ] ), self::UPDATE_CODE_NONCE_ACTION ) || empty( $_POST[ Referrals::REFERRAL_CODE_META_KEY ] ) ) {
				return false;
			}

			return sanitize_text_field( wp_unslash( $_POST[ Referrals::REFERRAL_CODE_META_KEY ] ) );
		};

		add_action(
			'user_profile_update_errors',
			function ( $errors, $update, $user ) use ( $get_code ): void {
				if ( ! $update ) {
					return;
				}

				$code = $get_code();

				if ( ! $code ) {
					return;
				}

				$user_id = goodbids()->referrals->get_user_id_by_referral_code( $code );

				// Referral code already used (and not by current user).
				if ( $user_id && $user_id !== intval( $user->ID ) ) {
					$errors->add( 'unique-ref-code', __( 'Submitted referral code is already in use.', 'goodbids' ) );
				}
			},
			10,
			3
		);

		add_action(
			'profile_update',
			function ( int $user_id ) use ( $get_code ): void {
				if ( ! current_user_can( 'promote_users' ) || ! current_user_can( 'edit_user', $user_id ) ) {
					return;
				}

				$code = $get_code();

				if ( ! $code ) {
					return;
				}

				$referrer = new Referrer( $user_id );
				$referrer->set_code( $code );
			}
		);
	}

	/**
	 * User AJAX Actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function user_ajax_actions(): void {
		// AJAX Delete Referral.
		add_action(
			'wp_ajax_goodbids_referrals_delete_referral',
			function () {
				list( $user_id, $referrer_id ) = $this->ajax_request_validation( 'goodbids_referrals_delete_referral_nonce' );

				goodbids()->referrals->delete( $referrer_id, $user_id );
				wp_send_json_success(
					[
						'done',
						$referrer_id,
						$user_id,
					]
				);
			}
		);

		// AJAX Add Referral.
		add_action(
			'wp_ajax_goodbids_referrals_add_referral',
			function () {
				list( $user_id, $referrer_id ) = $this->ajax_request_validation( 'goodbids_referrals_add_referral_nonce' );

				if ( $user_id === $referrer_id ) {
					wp_send_json_error(
						array(
							'error' => __( 'The referring user cannot be the same as he referred user.', 'goodbids' ),
						),
						200
					);
				}

				$referred_by = goodbids()->referrals->get_referrer_id( $user_id );

				if ( $referred_by ) {
					wp_send_json_error(
						[
							'error' => __( 'User already has a referrer. You might want to first delete the original referral.', 'goodbids' ),
						],
						200
					);
				}

				goodbids()->referrals->add_referral( $referrer_id, $user_id );

				wp_send_json_success(
					[
						'done',
						$referrer_id,
						$user_id,
					]
				);
			}
		);

		add_action(
			'wp_ajax_goodbids_referrals_search_user_select2',
			function () {
				// verify ajax nonce.
				check_ajax_referer( 'goodbids_referrals_search_user_select2', 'nonce' );

				$data     = wp_unslash( $_REQUEST );
				$per_page = 10;
				$page     = isset( $data['page'] ) ? intval( sanitize_text_field( $data['page'] ) ) : 1;
				$user_id  = isset( $data['user_id'] ) ? intval( sanitize_text_field( $data['user_id'] ) ) : 0;
				$exclude  = [];

				if ( $user_id ) {
					$referrer = new Referrer( $user_id );
					$exclude  = array_merge( $referrer->get_referred_user_ids(), [ $user_id ] );
				}

				$search      = isset( $data['term'] ) ? '*' . sanitize_text_field( $data['term'] ) . '*' : '';
				$search_cols = [ 'user_login', 'user_url', 'user_email', 'user_nicename', 'display_name' ];

				$users_query = new WP_User_Query(
					array(
						'blog_id'        => 0,
						'exclude'        => $exclude,
						'search'         => $search,
						'search_columns' => $search_cols,
						'orderby'        => 'user_registered',
						'order'          => 'DESC',
						'fields'         => [ 'ID', 'user_login', 'user_email', 'display_name' ],
						// pagination.
						'paged'          => $page,
						'number'         => $per_page,
						'count_total'    => true,
					)
				);

				$users       = $users_query->get_results();
				$total_count = $users_query->get_total();
				$users       = array_map(
					function ( \stdClass $user ) {
						$text = sprintf(
							'%d: %s (%s / %s )',
							esc_html( $user->ID ),
							esc_html( $user->display_name ),
							esc_html( $user->user_login ),
							esc_html( $user->user_email )
						);

						return [
							'id'   => $user->ID,
							'text' => esc_html( $text ),
						];
					},
					$users
				);

				wp_send_json(
					[
						'total'      => $total_count,
						'results'    => $users,
						'pagination' => [
							'more' => $total_count > ( $page * $per_page ),
						],
					]
				);
			}
		);
	}

	/**
	 * Set up JS Vars
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function user_js_vars(): void {
		add_action(
			'goodbids_enqueue_admin_script',
			function ( $handle ) {
				wp_localize_script(
					$handle,
					'goodbidsReferral',
					[
						'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
						'alert'          => [
							'title'       => __( 'Are you sure?', 'goodbids' ),
							'text'        => __( 'You won\'t be able to revert this!', 'goodbids' ),
							'confirmText' => __( 'Yes, delete it!', 'goodbids' ),
							'cancelText'  => __( 'Cancel', 'goodbids' ),
							'error'       => __( 'Something went wrong!', 'goodbids' ),
						],
						'confirmedAlert' => [
							'title' => __( 'Deleted!', 'goodbids' ),
							'text'  => __( 'The referral has been deleted.', 'goodbids' ),
						],
						'nonceDelete'    => wp_create_nonce( 'goodbids_referrals_delete_referral_nonce' ),
						'nonceAdd'       => wp_create_nonce( 'goodbids_referrals_add_referral_nonce' ),
					]
				);
			}
		);
	}

	/**
	 * Validate Nonce and return user_id and referrer_id
	 *
	 * @since 1.0.0
	 *
	 * @param string $nonce_action
	 *
	 * @return array
	 */
	private function ajax_request_validation( string $nonce_action ): array {
		check_ajax_referer( $nonce_action, 'nonce' );

		$data        = wp_unslash( $_POST );
		$user_id     = intvaL( sanitize_text_field( $data['user_id'] ) );
		$referrer_id = intval( sanitize_text_field( $data['referrer_id'] ) );

		if ( ! is_numeric( $user_id ) || ! ( new WP_User( $user_id ) ) || ! ( new WP_User( $referrer_id ) ) ) {
			wp_send_json_error(
				[
					'Invalid data',
					$referrer_id,
					$user_id,
				],
				422
			);
		}

		return [ $user_id, $referrer_id ];
	}

	/**
	 * Customize User Columns
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function customize_user_columns(): void {
		add_filter(
			'manage_users_columns',
			function ( array $columns ) {
				$columns['referred_by']    = __( 'Referred by', 'goodbids' );
				$columns['referral_count'] = __( 'Referral Count', 'goodbids' );
				return $columns;
			}
		);

		add_filter(
			'wpmu_users_columns',
			function ( array $columns ) {
				$columns['referred_by'] = __( 'Referred by', 'goodbids' );
				return $columns;
			}
		);

		add_filter(
			'manage_users_custom_column',
			function ( string $value, string $column, int $user_id ) {
				if ( ! $user_id ) {
					return '&mdash;';
				}

				$referrer = new Referrer( $user_id );

				if ( 'referral_count' === $column ) {
					return esc_html( $referrer->get_referral_count() );
				} elseif ( 'referred_by' === $column ) {
					$referrer_id = goodbids()->referrals->get_referrer_id( $user_id );

					if ( ! $referrer_id ) {
						return '&mdash;';
					}

					$referrer_username = get_userdata( $referrer_id )->user_login;

					if ( current_user_can( 'edit_user', $referrer_id ) ) {
						return sprintf( '<strong>%s</strong>', esc_html( $referrer_username ) );
					}

					$edit_url = esc_url( add_query_arg( 'wp_http_referer', rawurlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), get_edit_user_link( $referrer_id ) ) );

					return sprintf( '<strong><a href="%s">%s</a></strong>',
						esc_url( $edit_url ),
						esc_html( $referrer_username )
					);
				}

				return $value;
			},
			10,
			3
		);
	}
}
