<?php

namespace WPMailSMTP\Pro\BackupConnections;

use WPMailSMTP\ConnectionInterface;
use WPMailSMTP\Options;
use WPMailSMTP\Pro\BackupConnections\Admin\SettingsTab;
use WPMailSMTP\Pro\WPMailArgs;

/**
 * Class BackupConnections.
 *
 * @since 3.7.0
 */
class BackupConnections {

	/**
	 * The `wp_mail` function arguments that were used for current email sending.
	 *
	 * @since 3.7.0
	 *
	 * @var WPMailArgs
	 */
	private $current_wp_mail_args;

	/**
	 * Latest backup connection that was used for current email sending.
	 *
	 * @since 3.8.0
	 *
	 * @var ConnectionInterface
	 */
	private $latest_backup_connection;

	/**
	 * Register hooks.
	 *
	 * @since 3.7.0
	 */
	public function hooks() {

		// Init settings.
		( new SettingsTab() )->hooks();

		// Filter options save process.
		add_filter( 'wp_mail_smtp_options_set', [ $this, 'filter_options_set' ] );

		// Capture `wp_mail` function call.
		add_action( 'wp_mail_smtp_processor_capture_wp_mail_call', [ $this, 'capture_wp_mail_call' ] );
	}

	/**
	 * Capture `wp_mail` function call.
	 *
	 * @since 4.0.0
	 */
	public function capture_wp_mail_call() {
		/*
		 * We need to use original arguments that were passed to the `wp_mail` function without modifications,
		 * since `wp_mail` filter will be applied again in the backup email.
		 */
		$args = wp_mail_smtp()->get_processor()->get_original_wp_mail_args();

		if ( ! empty( $args ) ) {
			$this->set_current_backup_connection( $args );
		}
	}

	/**
	 * Set backup connection and capture `wp_mail` function arguments.
	 *
	 * @since 3.7.0
	 *
	 * @param array $args Array of the `wp_mail` function arguments.
	 *
	 * @return array
	 */
	public function set_current_backup_connection( $args ) {

		$this->current_wp_mail_args = new WPMailArgs( $args );

		$backup_connection_id = Options::init()->get( 'backup_connection', 'connection_id' );

		// Bail if the backup connection is not selected.
		if ( empty( $backup_connection_id ) ) {
			return $args;
		}

		$connections_manager = wp_mail_smtp()->get_connections_manager();
		$backup_connection   = $connections_manager->get_mail_backup_connection();

		// Check if the backup connection was not set previously.
		if ( is_null( $backup_connection ) ) {
			$backup_connection = $connections_manager->get_connection( $backup_connection_id, false );

			if ( ! empty( $backup_connection ) ) {
				$connections_manager->set_mail_backup_connection( $backup_connection );
			}
		}

		$this->latest_backup_connection = $backup_connection;

		return $args;
	}

	/**
	 * Get the latest backup connection.
	 *
	 * @since 3.8.0
	 *
	 * @return ConnectionInterface|null
	 */
	public function get_latest_backup_connection() {

		return $this->latest_backup_connection;
	}

	/**
	 * Whether the backup connection is defined and can be used for sending an email.
	 *
	 * @since 3.7.0
	 *
	 * @return bool
	 */
	public function is_ready() {

		$backup_connection = wp_mail_smtp()->get_connections_manager()->get_mail_backup_connection();

		return ! empty( $this->current_wp_mail_args ) && ! empty( $backup_connection );
	}

	/**
	 * Send email via the backup connection.
	 *
	 * @since 3.7.0
	 *
	 * @return bool
	 */
	public function send_email() {

		if ( empty( $this->current_wp_mail_args ) ) {
			return false;
		}

		$connections_manager = wp_mail_smtp()->get_connections_manager();
		$backup_connection   = $connections_manager->get_mail_backup_connection();

		if ( empty( $backup_connection ) ) {
			return false;
		}

		$args = array_merge(
			[
				'to'          => '',
				'subject'     => '',
				'message'     => '',
				'headers'     => '',
				'attachments' => [],
			],
			$this->current_wp_mail_args->get_args()
		);

		$connections_manager->reset_mail_connection();

		// Set backup connection as current mail connection.
		$connections_manager->set_mail_connection( $backup_connection );

		/**
		 * Fires before email sending via the backup connection.
		 *
		 * @since 3.7.0
		 *
		 * @param array $args Array of the `wp_mail` function arguments.
		 */
		do_action( 'wp_mail_smtp_pro_backup_connections_send_email_before', $args );

		$is_sent = wp_mail( $args['to'], $args['subject'], $args['message'], $args['headers'], $args['attachments'] );

		/**
		 * Fires after email sending via the backup connection.
		 *
		 * @since 3.7.0
		 *
		 * @param bool  $is_sent Whether the email was sent successfully or not.
		 * @param array $args    Array of the `wp_mail` function arguments.
		 */
		do_action( 'wp_mail_smtp_pro_backup_connections_send_email_after', $is_sent, $args );

		return $is_sent;
	}

	/**
	 * Sanitize options.
	 *
	 * @since 3.7.0
	 *
	 * @param array $options Currently processed options passed to a filter hook.
	 *
	 * @return array
	 */
	public function filter_options_set( $options ) {

		if ( ! isset( $options['backup_connection'] ) ) {
			$options['backup_connection'] = [
				'connection_id' => false,
			];

			return $options;
		}

		foreach ( $options['backup_connection'] as $key => $value ) {
			if ( $key === 'connection_id' ) {
				$options['backup_connection'][ $key ] = sanitize_key( $value );
			}
		}

		return $options;
	}
}
