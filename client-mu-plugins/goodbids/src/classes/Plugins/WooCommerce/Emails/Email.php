<?php
/**
 * Primary class used for extending custom WC Emails for GoodBids.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

use GoodBids\Auctions\Auction;
use GoodBids\Auctions\Watchers;
use WC_Email;
use WP_User;

/**
 * Custom GoodBids Email class.
 *
 * @since 1.0.0
 * @extends WC_Email
 */
class Email extends WC_Email {

	/**
	 * User ID.
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	public ?int $user_id = null;

	/**
	 * Email vars.
	 *
	 * @since 1.0.0
	 * @var string[]
	 */
	public array $email_vars = [];

	/**
	 * If email is sent to admins.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected bool $admin_email = false;

	/**
	 * If email is sent to Bidders.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected bool $bidder_email = false;

	/**
	 * If email is sent to Watchers.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected bool $watcher_email = false;

	/**
	 * Set default vars and placeholders
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Set up empty placeholders.
		$this->default_placeholders();
		$this->init_placeholders();

		parent::__construct();

		// Set up custom fields.
		$this->init_fields();

		// Add custom button support if button text exists.
		if ( $this->get_default_button_text() ) {
			$this->add_button_support();
		}

		// Default Template Customizations.
		add_action( 'woocommerce_email_header', [ $this, 'greeting_html' ], 12 );
		add_action( 'woocommerce_email_footer', [ $this, 'button_html' ], 5 );
		add_action( 'woocommerce_email_footer', [ $this, 'additional_content_html' ], 8 );
	}

	/**
	 * Add a custom field for button text.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_button_support(): void {
		/* translators: %s: list of placeholders */
		$placeholder_text  = sprintf( __( 'Available placeholders: %s', 'goodbids' ), '<code>' . esc_html( implode( '</code>, <code>', array_keys( $this->placeholders ) ) ) . '</code>' );

		// Custom Fields
		$this->set_form_field(
			'button_text',
			[
				'title'       => __( 'Button Text', 'woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $placeholder_text,
				'placeholder' => $this->get_default_button_text(),
				'default'     => '',
			]
		);
	}

	/**
	 * Initialize custom email vars.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_vars(): void {}

	/**
	 * Initialize custom email placeholders.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_placeholders(): void {}

	/**
	 * Initialize custom email fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_fields(): void {}


	/**
	 * Get the user's name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_user_name(): string {
		if ( ! $this->user_id ) {
			return '';
		}

		$user       = new WP_User( $this->user_id );
		$first_name = get_user_meta( $this->user_id, 'first_name', true );

		return $first_name ?: $user->user_login;
	}

	/**
	 * Custom Button Text Support. Override this method to add a custom button.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_default_button_text(): string {
		return '';
	}

	/**
	 * Get the button text.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_button_text(): string {
		/**
		 * Filter the button text.
		 * @since 1.0.0
		 *
		 * @param string $button_text The button text.
		 * @param object $object      The object.
		 * @param object $this        The email object.
		 */
		return apply_filters(
			'goodbids_button_text_' . $this->id,
			$this->format_string(
				$this->get_option( 'button_text', $this->get_default_button_text() )
			),
			$this->object,
			$this
		);
	}

	/**
	 * Get the button url.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_button_url(): string {
		return '#';
	}

	/**
	 * Output Button HTML if supported
	 *
	 * @since 1.0.0
	 *
	 * @param string $url
	 *
	 * @return void
	 */
	public function button_html( string $url = '' ): void {
		if ( ! $url ) {
			$url = $this->get_button_url();
		}

		if ( ! $this->get_button_text() || ! $url ) {
			return;
		}

		printf(
			/* translators: %1$s: reward checkout page url, %2$s: Claim Your Reward */
			'<p class="button-wrapper"><a class="button" href="%1$s">%2$s</a></p>',
			esc_html( $url ),
			esc_html( $this->get_button_text() )
		);
	}

	/**
	 * Determine if the email should actually be sent and setup email merge variables
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $object
	 * @param ?int  $user_id
	 *
	 * @return void
	 */
	public function trigger( mixed $object = null, ?int $user_id = null ): void {
		$this->setup_locale();

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		if ( $object ) {
			$this->object = $object;
		}

		if ( $user_id ) {
			$this->user_id = $user_id;
		}

		// Default Vars & Placeholders.
		$this->init_defaults();

		// Allow Email Classes to customize the vars.
		$this->init_vars();

		// Fill Placeholders with Values.
		$this->init_placeholders();

		// Woohoo, send the email!
		$this->send(
			$this->get_recipient(),
			$this->get_subject(),
			$this->get_content(),
			$this->get_headers(),
			$this->get_attachments()
		);

		$this->restore_locale();
	}

	/**
	 * Set default vars and placeholders
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_defaults(): void {
		// Default Vars.
		$this->add_email_var( 'instance', $this );
		$this->add_email_var( 'email_heading', $this->get_heading() );
		$this->add_email_var( 'button_text', $this->get_button_text() );
		$this->add_email_var( 'button_url', $this->get_button_url() );
		$this->add_email_var( 'additional_content', $this->get_additional_content() );

		$this->default_placeholders();
	}

	/**
	 * Set default placeholders
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function default_placeholders(): void {
		// Default Placeholders.
		$this->add_placeholder( '{login_url}', wc_get_page_permalink( 'authentication' ) );
		$this->add_placeholder( '{auctions_url}', get_post_type_archive_link( goodbids()->auctions->get_post_type() ) );

		// Auction Placeholders
		$auction = $this->object instanceof Auction ? $this->object : null;
		$reward  = goodbids()->rewards->get_product( $auction?->get_id() );

		// Auction Details.
		$this->add_placeholder( '{auction.url}', $auction?->get_url() );
		$this->add_placeholder( '{auction.title}', $auction?->get_title() );
		$this->add_placeholder( '{auction.start_date_time}', $auction?->get_start_date_time( 'n/j/Y g:i a' ) );
		$this->add_placeholder( '{auction.end_date_time}', $auction?->get_end_date_time( 'n/j/Y g:i a' ) );

		// Bid Details
		$this->add_placeholder( '{auction.starting_bid}', $auction?->get_starting_bid() );
		$this->add_placeholder( '{auction.bid_increment}', $auction?->get_bid_increment() );
		$this->add_placeholder( '{auction.bid_extension}', $auction?->get_bid_extension() );
		$this->add_placeholder( '{auction.high_bid}', $auction?->get_last_bid()?->get_subtotal() );

		// Auction Stats.
		$this->add_placeholder( '{auction.total_raised}', $auction?->get_total_raised() );
		$this->add_placeholder( '{auction.bid_count}', $auction?->get_bid_count() );
		$this->add_placeholder( '{auction.goal}', $auction?->get_goal() );
		$this->add_placeholder( '{auction.estimated_value}', $auction?->get_estimated_value() );
		$this->add_placeholder( '{auction.expected_high_bid}', $auction?->get_expected_high_bid() );

		// Reward Details.
		$this->add_placeholder( '{reward.title}', $reward?->get_title() );
		$this->add_placeholder( '{reward.type}', 'TBD' );
		$this->add_placeholder( '{reward.purchase_note}', $reward?->get_purchase_note() );
		$this->add_placeholder( '{reward.claim_url}', goodbids()->rewards->get_claim_reward_url( $auction?->get_id() ) );
		$this->add_placeholder( '{reward.days_to_claim}', 'TBD' );

		// User Details.
		$this->add_placeholder( '{user.name}', $this->get_user_name() );
		$this->add_placeholder( '{user.bid_count}', $auction?->get_user_bid_count( $this->user_id ) );
		$this->add_placeholder( '{user.last_bid_amount}', 'TBD' );
		$this->add_placeholder( '{user.total_donated}', $auction?->get_user_total_donated( $this->user_id ) );
	}
	/**
	 * Get the email recipient
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_recipient(): string {
		$recipient = parent::get_recipient();

		if ( $recipient ) {
			return $recipient;
		}

		// Check for a recipient value in the settings.
		$recipient = $this->get_option( 'recipient' );

		if ( $recipient ) {
			$recipients = array_map( 'trim', explode( ',', $recipient ) );
			$recipients = array_filter( $recipients, 'is_email' );
			return implode( ', ', $recipients );
		}

		if ( $this->is_admin_screen() ) {
			return $this->admin_screen_recipients();
		}

		$recipients = [];

		if ( $this->is_watcher_email() ) {
			$auction    = $this->object instanceof Auction ? $this->object : null;
			$watchers   = goodbids()->watchers->get_auction_watcher_emails( $auction?->get_id() );
			$recipients = array_merge( $recipients, $watchers );
		}

		// Set to customer email
		if ( $this->is_customer_email() || $this->is_bidder_email() ) {
			$recipients[] = get_userdata( $this->user_id )?->user_email;
		}

		// if none was entered, just use the WP admin email as a fallback
		if ( $this->is_admin_email() ) {
			$recipients[] = get_option( 'admin_email' );
		}

		$recipients = array_filter( $recipients, 'is_email' );

		return implode( ', ', $recipients );
	}

	/**
	 * Check if on Admin Screen
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function is_admin_screen(): bool {
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ( 'woocommerce_page_wc-settings' === $screen->id ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Display Generic Text in Admin Email Settings.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	private function admin_screen_recipients(): string {
		$recipients = [];

		if ( $this->is_watcher_email() ) {
			$recipients[] = __( 'Watchers', 'goodbids' );
		}

		if ( $this->is_bidder_email() ) {
			$recipients[] = __( 'Bidder', 'goodbids' );
		}

		if ( $this->is_admin_email() ) {
			$recipients[] = __( 'Admin', 'goodbids' );
		}

		if ( ! $recipients ) {
			return __( 'Not Set', 'goodbids' );
		}

		return implode( ', ', $recipients );
	}

	/**
	 * Check if the email is sent to the Admin.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_admin_email(): bool {
		return $this->admin_email;
	}

	/**
	 * Check if the email is sent to the Bidders.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_bidder_email(): bool {
		return $this->bidder_email;
	}

	/**
	 * Check if the email is sent to the Watchers.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_watcher_email(): bool {
		return $this->watcher_email;
	}

	/**
	 * Return the vars used in this email template.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_email_vars(): array {
		return $this->email_vars;
	}

	/**
	 * Add an email var for the templates.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return void
	 */
	protected function add_email_var( string $key, mixed $value ): void {
		$this->email_vars[ $key ] = $value;
	}

	/**
	 * Add an email var for the templates.
	 *
	 * @since 1.0.0
	 *
	 * @param string $token
	 * @param mixed  $value
	 *
	 * @return void
	 */
	protected function add_placeholder( string $token, mixed $value ): void {
		$this->placeholders[ $token ] = $value;
	}

	/**
	 * Get the HTML Email Content.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_content_html(): string {
		return wc_get_template_html(
			$this->template_html,
			$this->get_email_vars()
		);
	}

	/**
	 * Get the plain text email content.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_content_plain(): string {
		return wc_get_template_html(
			$this->template_plain,
			$this->get_email_vars()
		);
	}

	/**
	 * Add or change a form field.
	 * @since 1.0.0
	 *
	 * @param string $key
	 * @param array  $field
	 *
	 * @return void
	 */
	public function set_form_field( string $key, array $field ): void {
		// Update Existing.
		if ( array_key_exists( $key, $this->form_fields ) ) {
			$this->form_fields[ $key ] = $field;
			return;
		}

		// Add New before Additional Content.
		$form_fields = [];
		foreach ( $this->form_fields as $form_field_key => $form_field ) {
			if ( 'additional_content' === $form_field_key ) {
				$form_fields[ $key ] = $field;
			}

			$form_fields[ $form_field_key ] = $form_field;
		}

		$this->form_fields = $form_fields;
	}

	/**
	 * Output the Greeting HTML
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function greeting_html(): void {
		printf(
			'<p>%s %s,</p>',
			esc_html__( 'Hi', 'goodbids' ),
			esc_html( $this->get_user_name() )
		);
	}

	/**
	 * Show user-defined additional content - this is set in each email's settings
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function additional_content_html(): void {
		if ( $this->get_additional_content() ) {
			echo wp_kses_post( wpautop( wptexturize( $this->get_additional_content() ) ) );
		}
	}

	/**
	 * Display the plain text header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function plain_text_header(): void {
		echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
		echo esc_html( wp_strip_all_tags( $this->get_heading() ) );
		echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

		printf(
		/* translators: %s: Customer username */
			esc_html( wp_strip_all_tags( __( 'Hi %s,', 'goodbids' ) ) ),
			esc_html( wp_strip_all_tags( $this->get_user_name() ) )
		);

		echo "\n\n----------------------------------------\n\n";
	}

	/**
	 * Output the plain text footer
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function plain_text_footer(): void {
		echo "\n\n----------------------------------------\n\n";

		if ( $this->get_button_text() ) {
			printf(
				"%s:\n%s",
				esc_html( wp_strip_all_tags( wptexturize( $this->get_button_text() ) ) ),
				esc_html( wp_strip_all_tags( wptexturize( $this->get_button_url() ) ) ),
			);
			echo "\n\n----------------------------------------\n\n";
		}

		/**
		 * Show user-defined additional content - this is set in each email's settings.
		 */
		if ( $this->get_additional_content() ) {

			echo esc_html( wp_strip_all_tags( wptexturize( $this->get_additional_content() ) ) );
			echo "\n\n----------------------------------------\n\n";
		}
	}
}
