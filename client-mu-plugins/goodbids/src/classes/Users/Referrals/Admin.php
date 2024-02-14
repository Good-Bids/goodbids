<?php
/**
 * Referral Admin Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users\Referrals;

use WP_User;
use WP_User_Query;

/**
 * Class for Referral Admin
 *
 * @since 1.0.0
 */
class Admin {

	/**
	 * Initialize Referral Admin
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		$this->init();
	}

	/**
	 * Admin init
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init(): void {
		// Edit User Page.
		add_action( 'load-user-edit.php', [ $this, 'init_user' ] );
		add_action( 'load-users.php', [ $this, 'init_users' ] );

		// User AJAX Actions.
		$this->user_ajax_actions();
	}

	/**
	 * Perform User List Page Initialization
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_users(): void {
		// Add custom columns to Users table.
		$this->customize_user_columns();
	}

	/**
	 * Perform User Edit Page Initialization
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_user(): void {
		$profile_fields = function ( WP_User $user ): void {
			if ( ! current_user_can( 'promote_users' ) || ! current_user_can( 'edit_user', $user->ID ) ) {
				return;
			}

			$user_id  = $user->ID;
			$referral = new Referral( $user_id );
			$data     = compact( 'user_id', 'referral' );

			goodbids()->load_view( 'admin/referrals/invitations.php', $data );
			goodbids()->load_view( 'admin/referrals/update-referral-code.php', $data );
		};

		add_action( 'show_user_profile', $profile_fields, 1 );
		add_action( 'edit_user_profile', $profile_fields, 1 );

		add_action(
			'user_profile_update_errors',
			function ( $errors, $update, $user ): void {
				if ( ! $update ) {
					return;
				}

				if ( ! isset( $_POST['wrc_update_ref_code_nonce'] ) || ! wp_verify_nonce( $_POST['wrc_update_ref_code_nonce'], 'update_ref_code' ) ) {
					return;
				}

				$new_code = sanitize_text_field( wp_unslash( $_POST['wrc_new_ref_code'] ) );

				if ( empty( $new_code ) ) {
					return;
				}

				$referrer_id = goodbids()->referrals->get_user_id_by_code( $new_code );

				// Referral code hasn't been used yet
				if ( false === $referrer_id ) {
					return;
				}

				if ( $referrer_id !== $user->ID ) {
					$errors->add( 'unique-ref-code', __( 'Submitted refer code is already in use', 'goodbids' ) );
				}
			},
			10,
			3
		);

		add_action(
			'profile_update',
			function ( int $user_id ): void {
				if ( ! current_user_can( 'promote_users' ) || ! current_user_can( 'edit_user', $user_id ) ) {
					return;
				}

				if ( ! isset( $_POST['wrc_update_ref_code_nonce'] ) || ! wp_verify_nonce( $_POST['wrc_update_ref_code_nonce'], 'update_ref_code' ) ) {
					return;
				}

				$new_code = sanitize_text_field( wp_unslash( $_POST['wrc_new_ref_code'] ) );

				if ( empty( $new_code ) ) {
					return;
				}

				$referral = new Referral( $user_id );
				$referral->update( $new_code );
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
			'wp_ajax_goodbids_referrals_delete_user_relation',
			function () {
				list( $user_id, $referrer_id ) = $this->ajax_relation_request_validation( 'goodbids_referrals_delete_user_relation_nonce' );

				goodbids()->referrals->invitations->delete( $user_id, $referrer_id );
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
			'wp_ajax_goodbids_referrals_add_user_relation',
			function () {
				list( $user_id, $referrer_id ) = $this->ajax_relation_request_validation( 'goodbids_referrals_add_user_relation_nonce' );

				if ( $user_id === $referrer_id ) {
					wp_send_json_error(
						array(
							'error' => __( 'The referring user cannot be the same as he referred user.', 'goodbids' ),
						),
						200
					);
					wp_die();
				}

				$already_has_referrer = ! empty( get_user_meta( $user_id, Referrals::REFERRER_ID_META_KEY, true ) );

				if ( $already_has_referrer ) {
					wp_send_json_error(
						[
							'error' => __( 'User already has a referrer. You might want to first delete the original referral.', 'goodbids' ),
						],
						200
					);
					wp_die();
				}

				// set referrer as inviter of new user.
				goodbids()->referrals->invitations->add( $user_id, $referrer_id );
				update_user_meta( $user_id, Referrals::REFERRER_ID_META_KEY, $referrer_id );

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
				$page     = isset( $data['page'] ) ? sanitize_text_field( $data['page'] ) : 1;
				$per_page = 10;
				$user_id  = isset( $data['user_id'] ) ? sanitize_text_field( $data['user_id'] ) : 0;

				$excludes = ( new Referral( $user_id ) )->get_invitations();

				$search = isset( $data['term'] ) ? '*' . sanitize_text_field( $data['term'] ) . '*' : '';

				$users_query = new WP_User_Query(
					array(
						'exclude'        => $excludes,
						'search'         => $search,
						'search_columns' => [ 'user_login', 'user_url', 'user_email', 'user_nicename', 'display_name' ],
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
					function ( WP_User $user ) {
						$text = '(' . $user->ID . ') ' . $user->display_name . ' (' . $user->user_login . ')';

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
	 * Enqueue User Edit Assets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function enqueue_user_assets(): void {
		add_action(
			'admin_enqueue_scripts',
			function ( $hook ): void {
				if ( 'user-edit.php' !== $hook ) {
					return;
				}

				// Scripts.
				wp_enqueue_script( 'gb-referral-main', GOODBIDS_PLUGIN_URL . 'admin/js/main.min.js', [], goodbids()->get_version(), false );
				wp_enqueue_script( 'select2', GOODBIDS_PLUGIN_URL . 'admin/js/select2.full.min.js', [ 'jquery' ], goodbids()->get_version(), true );

				wp_localize_script(
					'gb-referral-main',
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
							'text'  => __( 'The relation has been deleted.', 'goodbids' ),
						],
						'nonceDelete'    => wp_create_nonce( 'goodbids_referrals_delete_user_relation_nonce' ),
						'nonceAdd'       => wp_create_nonce( 'goodbids_referrals_add_user_relation_nonce' ),
					]
				);

				// Styles.
				wp_enqueue_style( 'select2', GOODBIDS_PLUGIN_URL . 'admin/css/select2.min.css', [], goodbids()->get_version() );
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
	private function ajax_relation_request_validation( string $nonce_action ): array {
		check_ajax_referer( $nonce_action, 'nonce' );

		$data        = wp_unslash( $_POST );
		$user_id     = sanitize_text_field( $data['user_id'] );
		$referrer_id = sanitize_text_field( $data['referrer_id'] );

		if ( ! is_numeric( $user_id ) || ! ( new WP_User( $user_id ) ) || ! ( new WP_User( $referrer_id ) ) ) {
			wp_send_json_error(
				[
					'Invalid data',
					$referrer_id,
					$user_id,
				],
				422
			);
			wp_die();
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
				$columns['invited_by']       = __( 'Referred by', 'goodbids' );
				$columns['invitation_count'] = __( 'Invitation Count', 'goodbids' );
				return $columns;
			}
		);

		add_filter(
			'wpmu_users_columns',
			function ( array $columns ) {
				$columns['invited_by'] = __( 'Referred by', 'goodbids' );
				return $columns;
			}
		);

		add_filter(
			'manage_users_custom_column',
			function ( string $value, string $column, int $user_id ) {
				$referral = new Referral( $user_id );

				if ( 'invited_users_count' === $column ) {
					return esc_html( count( $referral->get_invitations() ) );
				} elseif ( 'invited_by' === $column ) {
					$referrer_id = $referral->get_referrer_id();

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
