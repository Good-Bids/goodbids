<?php
/**
 * Accessibility Checker pluign file.
 *
 * @package Accessibility_Checker
 */

/**
 * Log
 *
 * @param mixed $message message to log.
 * @return void
 */
function edacp_log( $message ) {
	// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_print_r, WordPress.WP.AlternativeFunctions.file_system_operations_fopen, WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fwrite
	$edac_log = dirname( __DIR__ ) . '/edacp_log.log';
	if ( is_array( $message ) || is_object( $message ) ) {
		$message = print_r( $message, true );
	}
	if ( file_exists( $edac_log ) ) {
		$file = fopen( $edac_log, 'a' );
		fwrite( $file, $message . "\n" );
	} else {
		$file = fopen( $edac_log, 'w' );
		fwrite( $file, $message . "\n" );
	}
	fclose( $file );
	// phpcs:enable WordPress.PHP.DevelopmentFunctions.error_log_print_r, WordPress.WP.AlternativeFunctions.file_system_operations_fopen, WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fwrite
}

/**
 * This function validates a table name against WordPress naming conventions and checks its existence in the database.
 *
 * The function first checks if the provided table name only contains alphanumeric characters, underscores, or hyphens.
 * If not, it returns null.
 *
 * After that, it checks if a table with that name actually exists in the database using the SHOW TABLES LIKE query.
 * If the table doesn't exist, it also returns null.
 *
 * If both checks are passed, it returns the valid table name.
 *
 * @param string $table_name The name of the table to be validated.
 *
 * @return string|null The validated table name, or null if the table name is invalid or the table does not exist.
 */
function edacp_get_valid_table_name( $table_name ) {

	// Check if table name only contains alphanumeric characters, underscores, or hyphens.
	if ( ! edacp_is_valid_table_name( $table_name ) ) {
		// Invalid table name.
		return null;
	}

	// Verify that the table actually exists in the database.
	if ( ! edacp_table_exists( $table_name ) ) {
		// Table does not exist.
		return null;
	}

	return $table_name;
}

/**
 * This function validates a table name against WordPress naming conventions
 *
 * @param string $table_name The name of the table to be validated.
 *
 * @return boolean
 */
function edacp_is_valid_table_name( $table_name ) {

	// Check if table name only contains alphanumeric characters, underscores, or hyphens.
	if ( ! preg_match( '/^[a-zA-Z0-9_\-]+$/', $table_name ) ) {
		// Invalid table name.
		return false;
	}
	return true;
}

/**
 * This function checks if a table exists in the database.
 *
 * @param string $table_name The name of the table to be checked.
 *
 * @return boolean
 */
function edacp_table_exists( $table_name ) {
	global $wpdb;

	// Verify that the table actually exists in the database.
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching -- This direct query is necessary to check if a table exists. Prepared statements cannot be used here.
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name ) {
		// Table does not exist.
		return false;
	}
	return true;
}

/**
 * Convert a unix timestamp to a datetime GMT
 *
 * @param integer $unix_timestamp - unix timestamp.
 * @return datetime - gmt timezone.
 */
function edacp_unix_to_gmt( $unix_timestamp = null ) {

	if ( null === $unix_timestamp ) {
		$unix_timestamp = time();
	}

	$date_time = \DateTime::createFromFormat( 'U', $unix_timestamp );
	if ( false === $date_time ) {
		return null;
	}

	return $date_time->setTimezone( new \DateTimeZone( 'GMT' ) );
}

/**
 * Convert a unix datetime GMT to a unix timestamp
 *
 * @param string $datetime_gmt - gmt timezone.
 * @return integer
 */
function edacp_gmt_to_unix( $datetime_gmt = null ) {

	if ( null === $datetime_gmt ) {
		return time();
	}

	$date_time = ( is_string( $datetime_gmt ) )
		? new \DateTime( $datetime_gmt )
		: $datetime_gmt;

	$date_time->setTimezone( new \DateTimeZone( 'GMT' ) );
	$timestamp = (int) $date_time->format( 'U' );

	return $timestamp;
}

/**
 * Unix timestamp to CST
 *
 * @param string $unix_timestamp - unix timestamp.
 * @return array - datetime CST timezone.
 */
function edacp_unix_to_cst( $unix_timestamp = null ) {

	if ( null === $unix_timestamp ) {
		$unix_timestamp = time();
	}

	$date_time = \DateTime::createFromFormat( 'U', $unix_timestamp );
	if ( false === $date_time ) {
		return null;
	}

	return $date_time->setTimezone( new \DateTimeZone( 'America/Chicago' ) );
}
