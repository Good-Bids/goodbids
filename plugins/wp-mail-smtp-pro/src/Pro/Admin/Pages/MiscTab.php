<?php

namespace WPMailSMTP\Pro\Admin\Pages;

use WPMailSMTP\Admin\Pages\MiscTab as MiscTabLite;
use WPMailSMTP\Helpers\UI;
use WPMailSMTP\Options;
use WPMailSMTP\Pro\Emails\RateLimiting\RateLimiting;

/**
 * Class MiscTab.
 *
 * @since 4.0.0
 */
class MiscTab extends MiscTabLite {

	/**
	 * Display rate limit settings.
	 *
	 * @since 4.0.0
	 */
	protected function display_rate_limit_settings() {

		$options = Options::init();

		$rate_limit_periods = [
			'minute' => esc_html__( 'Max emails per minute', 'wp-mail-smtp-pro' ),
			'hour'   => esc_html__( 'Max emails per hour', 'wp-mail-smtp-pro' ),
			'day'    => esc_html__( 'Max emails per day', 'wp-mail-smtp-pro' ),
			'week'   => esc_html__( 'Max emails per week', 'wp-mail-smtp-pro' ),
			'month'  => esc_html__( 'Max emails per month', 'wp-mail-smtp-pro' ),
		];
		?>
		<div class="wp-mail-smtp-setting-group">
			<div id="wp-mail-smtp-setting-row-rate_limit" class="wp-mail-smtp-setting-row wp-mail-smtp-clear">
				<div class="wp-mail-smtp-setting-label">
					<label for="<?php echo 'wp-mail-smtp-setting-' . esc_attr( $this->get_slug() ) . '-rate_limit_enabled'; ?>">
						<?php esc_html_e( 'Email Rate Limiting', 'wp-mail-smtp-pro' ); ?>
					</label>
				</div>
				<div class="wp-mail-smtp-setting-field">
					<?php
					UI::toggle(
						[
							'name'    => 'wp-mail-smtp[rate_limit][enabled]',
							'id'      => 'wp-mail-smtp-setting-' . esc_attr( $this->get_slug() ) . '-rate_limit_enabled',
							'value'   => 'true',
							'checked' => RateLimiting::is_enabled(),
						]
					);
					?>
					<p class="desc">
					<?php
					printf(
						wp_kses( /* translators: %1$s - Documentation URL. */
							__( 'Limit the number of emails this site will send in each time interval (per minute, hour, day, week and month). Emails that will cross those set limits will be queued and sent as soon as your limits allow. <a href="%1$s" target="_blank" rel="noopener noreferrer">Learn More</a>', 'wp-mail-smtp-pro' ),
							[
								'a' => [
									'href'   => [],
									'rel'    => [],
									'target' => [],
								],
							]
						),
						esc_url(
							wp_mail_smtp()->get_utm_url(
								'https://wpmailsmtp.com/docs/a-complete-guide-to-miscellaneous-settings/#email-rate-limiting',
								[
									'medium'  => 'misc-settings',
									'content' => 'Email Rate Limiting - support article',
								]
							)
						)
					);
					?>
					</p>
				</div>
			</div>
			<div id="wp-mail-smtp-setting-row-rate_limit_periods" style="display: <?php echo (bool) $options->get( 'rate_limit', 'enabled' ) ? 'block' : 'none'; ?>">
				<div class="wp-mail-smtp-setting-label"></div>
				<div class="wp-mail-smtp-setting-field">
					<?php $this->display_enqueued_emails_count_notice(); ?>
					<?php foreach ( $rate_limit_periods as $period => $label ) : ?>
						<div class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-text wp-mail-smtp-clear">
							<div class="wp-mail-smtp-setting-label">
								<label for="wp-mail-smtp-setting-rate_limit_<?php echo esc_attr( $period ); ?>"><?php echo esc_html( $label ); ?></label>
							</div>
							<div class="wp-mail-smtp-setting-field">
								<input type="number"
									   name="wp-mail-smtp[rate_limit][<?php echo esc_attr( $period ); ?>]"
									   value="<?php echo esc_attr( $options->get( 'rate_limit', $period ) ); ?>"
									   min="0"
								/>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<script>
			jQuery( '#wp-mail-smtp-setting-misc-rate_limit_enabled' ).on( 'change', function() {
				if ( jQuery( this ).is( ':checked' ) ) {
					jQuery( '#wp-mail-smtp-setting-row-rate_limit_periods' ).show();
				} else {
					jQuery( '#wp-mail-smtp-setting-row-rate_limit_periods' ).hide();
				}
			} );
		</script>
		<?php
	}

	/**
	 * Process tab form submission ($_POST).
	 *
	 * @since 4.0.0
	 *
	 * @param array $data Tab data specific for the plugin ($_POST).
	 */
	public function process_post( $data ) {

		if ( empty( $data['rate_limit']['enabled'] ) ) {
			$data['rate_limit']['enabled'] = false;
		}

		parent::process_post( $data );
	}

	/**
	 * Display a notice with the current count
	 * of enqueued emails.
	 *
	 * @since 4.0.0
	 */
	private function display_enqueued_emails_count_notice() {

		$queue = wp_mail_smtp()->get_queue();

		// We're checking for the queue being enabled explictly,
		// just in case a 3rd party filter is turning it off.
		if ( ! $queue->is_enabled() ) {
			return;
		}

		$enqueued_emails_count = $queue->count_queued_emails();

		if ( $enqueued_emails_count === 0 ) {
			return;
		}

		$message = sprintf( /* translators: %1$s: Singular/Plural string; %2$d - enqueued email count; %3$s: Singular/Plural string. */
			esc_html__( 'Currently there %1$s %2$d %3$s in the queue.', 'wp-mail-smtp-pro' ),
			_n( 'is', 'are', $enqueued_emails_count, 'wp-mail-smtp-pro' ),
			$enqueued_emails_count,
			_n( 'email', 'emails', $enqueued_emails_count, 'wp-mail-smtp-pro' )
		);
		?>
		<div class="notice inline notice-inline wp-mail-smtp-notice notice-info" style="margin: 10px 0 0;">
			<p>
				<?php echo esc_html( $message ); ?>
			</p>
		</div>
		<?php
	}
}
