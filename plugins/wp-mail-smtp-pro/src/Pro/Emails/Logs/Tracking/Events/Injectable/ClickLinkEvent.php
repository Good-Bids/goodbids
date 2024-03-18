<?php

namespace WPMailSMTP\Pro\Emails\Logs\Tracking\Events\Injectable;

use WPMailSMTP\Helpers\Helpers;
use WPMailSMTP\Pro\Emails\Logs\Tracking\Tracking;
use DOMDocument;
use WP_REST_Response;

/**
 * Email tracking click link event class.
 *
 * @since 2.9.0
 */
class ClickLinkEvent extends AbstractInjectableEvent {

	/**
	 * Get the event type.
	 *
	 * @since 2.9.0
	 *
	 * @return string
	 */
	public static function get_type() {

		return 'click-link';
	}

	/**
	 * Whether the tracking event is enabled or not.
	 *
	 * @since 2.9.0
	 *
	 * @return bool
	 */
	public function is_active() {

		return wp_mail_smtp()->get_pro()->get_logs()->is_enabled_click_link_tracking();
	}

	/**
	 * Inject tracking link to each link in email content.
	 *
	 * @since 2.9.0
	 *
	 * @param string $email_content Email content.
	 *
	 * @return string Email content with injected tracking code.
	 */
	public function inject( $email_content ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		// Skip if DOMDocument is not available.
		if ( ! class_exists( 'DOMDocument' ) ) {
			return $email_content;
		}

		$html  = make_clickable( $email_content );
		$links = $this->get_all_links( $html );

		if ( empty( $links ) ) {
			return $email_content;
		}

		$created_links = [];

		foreach ( $links as $link ) {
			$url = $link['url'];

			// Skip empty, anchor or mailto links.
			if (
				strlen( trim( $url ) ) === 0 ||
				substr( trim( $url ), 0, 1 ) === '#' ||
				substr( trim( $url ), 0, 6 ) === 'mailto'
			) {
				continue;
			}

			/**
			 * Filters whether current url is trackable or not.
			 *
			 * @since 2.9.0
			 *
			 * @param bool   $is_trackable Whether url is trackable or not.
			 * @param string $url          Current url.
			 */
			$is_trackable_url = apply_filters(
				'wp_mail_smtp_pro_emails_logs_tracking_events_injectable_click_link_event_inject_link',
				true,
				$url
			);

			if ( ! $is_trackable_url || isset( $created_links[ $url ] ) ) {
				continue;
			}

			$link_id = $this->add_link( $url );

			// Skip if link was not created.
			if ( $link_id === false ) {
				continue;
			}

			$created_links[ $url ] = $link_id;

			$tracking_url = $this->get_tracking_url(
				[
					'object_id' => $link_id,
					'url'       => rawurlencode( $url ),
				]
			);

			$html = str_replace( $link['attr'], 'href="' . $tracking_url . '"', $html );
		}

		return $html;
	}

	/**
	 * Persist event data to DB.
	 *
	 * @since 2.9.0
	 *
	 * @return int|false Event ID or false if saving failed.
	 */
	public function persist() {

		// In case if images loading disabled in email, create open email event when first link in email clicked.
		$open_event = new OpenEmailEvent( $this->get_email_log_id() );

		if ( ! $open_event->was_event_already_triggered() ) {
			$open_event->persist();
		}

		return parent::persist();
	}

	/**
	 * Redirect user to actual url.
	 *
	 * @since 2.9.0
	 *
	 * @param array $event_data Event data from request.
	 *
	 * @return WP_REST_Response REST response.
	 */
	public function get_response( $event_data ) {

		$response = new WP_REST_Response();

		$response->header( 'Cache-Control', 'must-revalidate, no-cache, no-store, max-age=0, no-transform' );
		$response->header( 'Pragma', 'no-cache' );
		$response->set_status( 301 );
		$response->header( 'Location', $this->normalize_url( urldecode( $event_data['url'] ) ) );

		return $response;
	}

	/**
	 * Save tracked link to DB.
	 *
	 * @since 2.9.0
	 *
	 * @param string $url Actual url form email.
	 *
	 * @return false|int Link ID.
	 */
	public function add_link( $url ) {

		global $wpdb;

		$data = [
			'email_log_id' => intval( $this->get_email_log_id() ),
			'url'          => esc_url_raw( $url ),
		];

		$result = $wpdb->insert( Tracking::get_links_table_name(), $data, [ '%d', '%s' ] );

		return $result !== false ? $wpdb->insert_id : false;
	}

	/**
	 * Delete all email related tracked links.
	 *
	 * @since 2.9.0
	 *
	 * @return bool
	 */
	public function delete_links() {

		global $wpdb;

		$email_log_id = intval( $this->get_email_log_id() );

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
		return $wpdb->delete( Tracking::get_links_table_name(), [ 'email_log_id' => $email_log_id ], [ '%d' ] );
	}

	/**
	 * Get all links from email content.
	 *
	 * @since 4.0.0
	 *
	 * @param string $email_content Email content.
	 *
	 * @return array
	 */
	private function get_all_links( $email_content ) {

		// Insert each </a> into new line.
		$email_content = str_replace( '</a>', "</a>\n", $email_content );

		// Find all link tags.
		preg_match_all( '/<a(.*)>.*<\/a>/isU', $email_content, $links, PREG_SET_ORDER );

		if ( empty( $links ) ) {
			return [];
		}

		$links  = array_column( $links, 1 );
		$result = [];

		foreach ( $links as $link_attrs ) {
			// Find href attribute with double quotes.
			preg_match_all( '/href=\"([^\r\n]*)\"/iU', $link_attrs, $href, PREG_SET_ORDER );

			// If href attribute not found, try to find it with single quotes.
			if ( empty( $href ) ) {
				preg_match_all( '/href=\'([^\r\n]*)\'/iU', $link_attrs, $href, PREG_SET_ORDER );
			}

			if ( empty( $href ) ) {
				continue;
			}

			$result[] = [
				'url'  => $href[0][1],
				'attr' => $href[0][0],
			];
		}

		return $result;
	}

	/**
	 * Normalize url.
	 *
	 * Mainly used to decode html entities like &amp; to &.
	 * To make it as safe as possible the `DOMDocument` is used.
	 *
	 * @since 4.0.2
	 *
	 * @param string $url Url to normalize.
	 *
	 * @return string
	 */
	private function normalize_url( $url ) {

		// Skip if DOMDocument is not available.
		if ( ! class_exists( 'DOMDocument' ) ) {
			return $url;
		}

		if ( ! function_exists( 'mb_encode_numericentity' ) ) {
			Helpers::include_mbstring_polyfill();
		}

		$link_html         = '<a href="' . $url . '" id="link"></a>';
		$encoded_link_html = mb_encode_numericentity( $link_html, [ 0x80, 0x10FFFF, 0, ~0 ], 'UTF-8' );

		if ( ! empty( $encoded_link_html ) ) {
			$link_html = $encoded_link_html;
		}

		$dom = new DOMDocument();

		$dom->loadHTML( $link_html );

		$link = $dom->getElementById( 'link' );

		if ( empty( $link ) ) {
			return $url;
		}

		$normalized_url = $link->getAttribute( 'href' );

		if ( empty( $normalized_url ) ) {
			return $url;
		}

		return $normalized_url;
	}
}
