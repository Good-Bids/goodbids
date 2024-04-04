<?php
/*
Plugin Name: GoodBids - Batch Upload for Multisite with CSV Validation
Plugin URI: http://goodbids.com/
Description: Adds a 'Batch Upload' page with CSV upload and validation to the network admin settings section in WordPress Multisite.
Version: 1.0
Author: GoodBids
Author URI: http://goodbids.com/
Network: true
*/

// Hook for adding admin menus
add_action( 'network_admin_menu', 'goodbids_batch_upload_menu' );

// Action function for the above hook
function goodbids_batch_upload_menu() {
	// Check if the current user is a network admin
	if ( current_user_can( 'manage_network' ) ) {
		// Add a new submenu under Settings:
		add_submenu_page(
			'settings.php',          // Parent menu slug
			'Batch Upload',          // Page title
			'Batch Upload',          // Menu title
			'manage_network_options', // Capability
			'batch-upload',          // Menu slug
			'goodbids_batch_upload_page'      // Function
		);
	}
}

// Display the Batch Upload page
function goodbids_batch_upload_page() {
	$toEmail = 'partners@goodbids.org';
	$ccEmail = 'seth@goodbids.org';

	if ( ! current_user_can( 'manage_network' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'goodbids' ) );
	}

	echo '<div class="wrap">';
	echo '<h2>' . __( 'Batch Upload', 'goodbids' ) . '</h2>';

	if ( isset( $_POST['batch_upload_nonce'] ) && wp_verify_nonce( $_POST['batch_upload_nonce'], 'batch_upload_action' ) ) {
		if ( isset( $_FILES['batch_upload_csv'] ) ) {
			$file     = $_FILES['batch_upload_csv'];
			$filename = $file['tmp_name'];

			if ( ( $handle = fopen( $filename, 'r' ) ) !== false ) {
				fgetcsv( $handle, 1000, ',' ); // Skip the header row

				$errors        = [];
				$success_sites = [];
				$failed_sites  = [];
				$row_number    = 1;

				// Disable the "New Site Registration" email
				add_filter( 'wpmu_welcome_notification', '__return_false' );

				// Disable the "Admin Email Changed" email
				add_filter( 'update_option_new_admin_email', '__return_false', 10, 2 );

				while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
					$row_number ++;

					if ( count( $data ) !== 11 ) {
						$errors[] = sprintf( __( 'Error on row %d: incorrect number of columns', 'goodbids' ), $row_number );
						continue;
					}

					$fileName        = trim( $data[0] );
					$ein             = trim( $data[1] );
					$website         = trim( $data[2] );
					$contactName     = trim( $data[3] );
					$contactEmail    = trim( $data[4] );
					$contactTitle    = trim( $data[5] );
					$blogName        = trim( $data[6] );
					$blogDescription = trim( $data[7] );
					$timezone        = trim( $data[8] );
					$site_url        = trim( $data[9] );
					$home            = trim( $data[10] );

					// Skip empty rows
					if ( empty( $fileName ) && empty( $ein ) && empty( $website ) && empty( $contactName ) && empty( $contactEmail ) && empty( $contactTitle ) && empty( $blogName ) && empty( $blogDescription ) && empty( $timezone ) && empty( $site_url ) && empty( $home ) ) {
						continue;
					}

					$row_errors = [];

					if ( empty( $fileName ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: Legal Name is missing', 'goodbids' ), $row_number );
					}

					if ( empty( $ein ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: EIN is missing', 'goodbids' ), $row_number );
					}
					elseif ( ! preg_match( '/^\d{2}-\d{7}$/', $ein ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: EIN format is incorrect', 'goodbids' ), $row_number );
					}

					if ( empty( $website ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: Website is missing', 'goodbids' ), $row_number );
					}
					elseif ( ! goodbids_validate_domain( $website ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: Website value is invalid', 'goodbids' ), $row_number );
					}

					if ( empty( $contactName ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: Primary Contact legal_name is missing', 'goodbids' ), $row_number );
					}

					if ( empty( $contactEmail ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: Primary Contact Email Address is missing', 'goodbids' ), $row_number );
					}
					elseif ( ! filter_var( $contactEmail, FILTER_VALIDATE_EMAIL ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: Primary Contact Email Address is invalid', 'goodbids' ), $row_number );
					}

					if ( empty( $contactTitle ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: Primary Contact Job Title is missing', 'goodbids' ), $row_number );
					}

					if ( empty( $blogName ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: Blog Name is missing', 'goodbids' ), $row_number );
					}

					if ( empty( $blogDescription ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: Blog Description is missing', 'goodbids' ), $row_number );
					}

					if ( empty( $timezone ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: timezone_string is missing', 'goodbids' ), $row_number );
					}
					elseif ( ! in_array( $timezone, timezone_identifiers_list() ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: timezone_string is invalid', 'goodbids' ), $row_number );
					}

					if ( empty( $site_url ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: site_url is missing', 'goodbids' ), $row_number );
					}
					elseif ( ! goodbids_validateUrl( $site_url ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: site_url is invalid', 'goodbids' ), $row_number );
					}

					if ( empty( $home ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: home value is missing', 'goodbids' ), $row_number );
					}
					elseif ( ! goodbids_validateUrl( $home ) ) {
						$row_errors[] = sprintf( __( 'Error on row %d: home value is invalid', 'goodbids' ), $row_number );
					}

					// Skip site creation if there are any validation errors
					if ( ! empty( $row_errors ) ) {
						$errors         = array_merge( $errors, $row_errors );
						$failed_sites[] = $site_url;
						continue;
					}

					$main_domain = get_network()->domain;
					$user_id     = get_current_user_id();
					$path        = parse_url( $site_url, PHP_URL_PATH );

					$result = wpmu_create_blog( $main_domain, $path, $blogName, $user_id, [ 'public' => 1 ], get_current_network_id() );

					if ( $result instanceof WP_Error ) {
						$errors[]       = sprintf( __( 'Error on row %d: error creating site for domain: %s. Error message: %s', 'goodbids' ), $row_number, esc_html( $main_domain . $path ), $result->get_error_message() );
						$failed_sites[] = $site_url;
					}
					else {
						$success_sites[] = $site_url;

						$processed_data = [
							'legal_name'                => $fileName,
							'EIN'                       => $ein,
							'website'                   => $website,
							'primary_contact_name'      => $contactName,
							'primary_contact_email'     => $contactEmail,
							'primary_contact_job_title' => $contactTitle,
							'blog_name'                 => $blogName,
							'blog_description'          => $blogDescription,
							'timezone_string'           => $timezone,
							'subdirectory_path'         => $path,
						];

						goodbids_update_site_options( $result, $processed_data );
					}
				}

				// Re-enable the "New Site Registration" email
				remove_filter( 'wpmu_welcome_notification', '__return_false' );

				// Re-enable the "Admin Email Changed" email
				remove_filter( 'update_option_new_admin_email', '__return_false', 10 );

				fclose( $handle );

				if ( ! empty( $errors ) ) {
					echo '<div class="notice notice-error"><p>' . __( 'CSV file contains errors. Please correct the data and try again.', 'goodbids' ) . '</p>';
					echo '<ul>';
					foreach ( $errors as $error ) {
						echo '<li>' . esc_html( $error ) . '</li>';
					}
					echo '</ul></div>';
				}

				if ( ! empty( $success_sites ) ) {
					echo '<div class="notice notice-success"><p>' . sprintf( __( '%d site(s) created successfully:', 'goodbids' ), count( $success_sites ) ) . '</p>';
					echo '<ul>';
					foreach ( $success_sites as $site ) {
						echo '<li>' . esc_html( $site ) . '</li>';
					}
					echo '</ul></div>';
				}

				if ( ! empty( $failed_sites ) ) {
					echo '<div class="notice notice-error"><p>' . sprintf( __( '%d site(s) failed to be created:', 'goodbids' ), count( $failed_sites ) ) . '</p>';
					echo '<ul>';
					foreach ( $failed_sites as $site ) {
						echo '<li>' . esc_html( $site ) . '</li>';
					}
					echo '</ul></div>';
				}

				$subject = 'Batch Upload Results';
				$message = "Batch upload completed.\n\n";

				if ( ! empty( $success_sites ) ) {
					$message .= "The following sites were created successfully:\n";
					$message .= implode( "\n", $success_sites );
					$message .= "\n\n";
				}

				if ( ! empty( $failed_sites ) ) {
					$message .= "The following sites failed to be created:\n";
					$message .= implode( "\n", $failed_sites );
					$message .= "\n\n";
				}

				if ( ! empty( $errors ) ) {
					$message .= "The following errors occurred:\n";
					$message .= implode( "\n", array_map( function ( $error ) {
						return '- ' . $error;
					}, $errors ) );
					$message .= "\n\n";
				}

				$headers = array(
					'Cc: ' . $ccEmail
				);
				wp_mail($toEmail, $subject, $message, $headers);
			}
		}
	}

	echo '<form method="post" enctype="multipart/form-data">';
	wp_nonce_field( 'batch_upload_action', 'batch_upload_nonce' );
	echo '<input type="file" name="batch_upload_csv" accept=".csv" required>';
	submit_button( __( 'Upload CSV', 'goodbids' ) );
	echo '</form>';

	// Add a link to the CSV file
	$csv_file_url = plugins_url( 'example.csv', __FILE__ );
	echo '<p>Download the example CSV file: <a href="' . esc_url( $csv_file_url ) . '">example.csv</a></p>';

	echo '</div>';
}

function goodbids_validateUrl( $url ) {

	// Validate the modified URL
	if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {

		return true;

	}
	else {

		return false;

	}
}

// Function to update site options based on dynamic fields
function goodbids_update_site_options( $blog_id, $data ) {

	$legalName        = $data['legal_name'];
	$EIN              = $data['EIN'];
	$website          = $data['website'];
	$contactName      = $data['primary_contact_name'];
	$contactEmail     = $data['primary_contact_email'];
	$contactTitle     = $data['primary_contact_job_title'];
	$blogName         = $data['blog_name'];
	$blogDescription  = $data['blog_description'];
	$subdirectoryPath = $data['subdirectory_path']; // Get subdirectory path from processed data
	$timezone_string  = $data['timezone_string'];

	$dynamic_fields = [
		'EIN'                               => 'EIN',
		'legal_name'                        => 'legal_name',
		'primary_contact_email_address'     => 'same as primary contact email address',
		'primary_contact_job_title'         => 'same as primary primary contact job title',
		'home'                              => 'same as site url',
		'siteurl'                           => 'same as site url',
		'finance_contact_legal_name'        => 'legal_name',
		'finance_contact_email_address'     => 'same as primary contact email address',
		'finance_contact_job_title'         => 'same as primary primary contact job title',
		'admin_email'                       => 'same as primary contact email',
		'woocommerce_email_from_name'       => 'blogname',
		'woocommerce_stock_email_recipient' => 'same as primary contact email',
		'goodbids_onboarded'                => 'upload time',
		'new_admin_email'                   => 'same as admin email',
		'blogdescription'                   => 'blogdescription',
		'timezone_string'                   => 'timezone string',
		'primary_contact_legal_name'        => 'primary contact legal name',
		'website'                           => 'website',

	];

	foreach ( $dynamic_fields as $field => $default_value ) {
		$value = '';

		switch ( $default_value ) {
			case 'legal_name':
				$value = $legalName;
				break;
			case 'EIN':
				$value = $EIN;
				break;
			case 'same as primary contact email address':
				$value = $contactEmail;
				break;
			case 'same as primary primary contact job title':
				$value = $contactTitle;
				break;
			case 'same as site url':
				$value = network_site_url( $subdirectoryPath ); // Use subdirectory path to generate site URL
				break;
			case 'blogname':
				$value = $blogName;
				break;
			case 'upload time':
				$value = current_time( 'mysql' );
				break;
			case 'same as primary contact email':
			case 'same as admin email':
				$value = $contactEmail;
				break;
			case 'blogdescription':
				$value = $blogDescription;
				break;
			case 'timezone string':
				$value = $timezone_string;
				break;
			case 'primary contact legal name':
				$value = $contactName;
				break;
			case 'website':
				$value = $website;
				break;
			default:
				$value = $default_value;
		}

		update_blog_option( $blog_id, $field, $value );
	}
}


function goodbids_validate_domain( $domain ) {
	// Remove any leading/trailing spaces
	$domain = trim( $domain );

	// Convert to lowercase for consistent comparison
	$domain = strtolower( $domain );

	// Check if the domain starts with "www." and contains valid characters
	if ( strpos( $domain, 'www.' ) === 0 && preg_match( '/^[a-z0-9.-]+$/', $domain ) ) {
		return true; // Valid domain
	}
	else {
		return false; // Invalid domain
	}
}

?>
