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
use GoodBids\Frontend\Request;
use GoodBids\Users\FreeBid;
use GoodBids\Users\Referrals\Referrer;
use GoodBids\Utilities\Log;
use WC_Email;
use WC_Order;
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
	 * If email is sent to Super Admins.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected bool $super_admins_email = false;

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
	 * Default Recipient to empty string so it doesn't break WC get_recipient method.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $recipient = '';

	/**
	 * Set default vars and placeholders
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( $this->has_initialized() ) {
			return;
		}

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

		// Make sure the email is initialized only once.
		$this->register_initialized();
	}

	/**
	 * Register this email has been initialized.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_initialized(): void {
		if ( ! $this->id ) {
			return;
		}

		goodbids()->woocommerce->emails->register_initialized( $this->id );
	}

	/**
	 * Check if this email has been initialized.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function has_initialized(): bool {
		if ( ! $this->id ) {
			return false;
		}

		return goodbids()->woocommerce->emails->has_initialized( $this->id );
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
		$placeholder_text = sprintf( __( 'Available placeholders: %s', 'goodbids' ), '<code>' . esc_html( implode( '</code>, <code>', array_keys( $this->placeholders ) ) ) . '</code>' );

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
	 * Initialize custom email hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_customizations(): void {}

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
		 *
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
	 * @return void
	 */
	public function button_html(): void {
		if ( ! $this->get_button_text() || ! $this->get_button_url() ) {
			return;
		}

		printf(
			/* translators: %1$s: reward checkout page url, %2$s: Claim Your Reward */
			'<p class="button-wrapper"><a class="button" href="%1$s">%2$s</a></p>',
			esc_html( $this->get_button_url() ),
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

		if ( $object ) {
			$this->object = $object;
		}

		if ( $user_id ) {
			$this->user_id = $user_id;
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		// Initialize template variables and hooks.
		$this->init_template();

		// Woohoo, send the email!
		$this->send(
			$this->get_recipient(),
			goodbids()->utilities->sanitize_email_subject( $this->get_subject() ),
			$this->get_content(),
			$this->get_headers(),
			$this->get_attachments()
		);

		// Reset the template.
		$this->reset_template();

		$this->restore_locale();
	}

	/**
	 * Perform Placeholder Replacements throughout the content.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_content(): string {
		return $this->format_string( parent::get_content() );
	}

	/**
	 * Set template vars and placeholders
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_template(): void {
		// Default placeholders.
		$this->default_placeholders();

		// Fill Placeholders with Values.
		$this->init_placeholders();

		// Default Vars.
		$this->default_vars();

		// Allow Email Classes to customize the vars.
		$this->init_vars();

		// Default Customizations.
		$this->default_customizations();

		// Allow emails to be customized.
		$this->init_customizations();
	}

	/**
	 * Reset the template after sending the email.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function reset_template(): void {
		// Remove Default Customizations.
		$this->remove_default_customizations();

		// Remove Template Customizations.
		$this->remove_customizations();
	}

	/**
	 * Remove default hooks
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function remove_default_customizations(): void {
		remove_action( 'woocommerce_email_header', [ $this, 'greeting_html' ], 12 );
		remove_action( 'woocommerce_email_footer', [ $this, 'button_html' ], 5 );
		remove_action( 'woocommerce_email_footer', [ $this, 'additional_content_html' ], 8 );
	}

	/**
	 * Templates can remove their customizations.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function remove_customizations(): void {}

	/**
	 * Set default placeholders
	 *
	 * Placeholders should be present at all times. If the related object or ID is not yet set, default to a blank string.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function default_placeholders(): void {
		$date_format     = 'F j, Y';
		$datetime_format = sprintf(
			'%s %s g:ia',
			$date_format,
			addcslashes( __( 'at', 'goodbids' ), 'A..z' )
		);

		// Get Email Object
		$auction  = $this->object instanceof Auction ? $this->object : null;
		$order    = $this->object instanceof WC_Order ? $this->object : null;
		$free_bid = $this->object instanceof FreeBid ? $this->object : null;
		$request  = $this->object instanceof Request ? $this->object : null;

		if ( $order ) {
			$auction_id = goodbids()->woocommerce->orders->get_auction_id( $order->get_id() );
			$auction    = goodbids()->auctions->get( $auction_id );
		}

		$reward = goodbids()->rewards->get_product( $auction?->get_id() );

		// Default Placeholders.
		$this->add_placeholder( '{login_url}', wc_get_page_permalink( 'authentication' ) );
		$this->add_placeholder( '{auctions_url}', get_post_type_archive_link( goodbids()->auctions->get_post_type() ) );

		// Auction Details.
		$auction_title = $auction?->get_title() ?: '';
		$this->add_placeholder( '{auction.url}', $auction?->get_url() );
		$this->add_placeholder( '{auction.admin_url}', get_edit_post_link( $auction?->get_id() ) );
		$this->add_placeholder( '{auction.title}', htmlspecialchars( $auction_title ) );
		$this->add_placeholder( '{auction.start_date_time}', $auction?->get_start_date_time( $datetime_format ) );
		$this->add_placeholder( '{auction.end_date_time}', $auction?->get_end_date_time( $datetime_format ) );

		// Bid Details
		$starting_bid = wc_price( $auction?->get_starting_bid() );
		$this->add_placeholder( '{auction.starting_bid}', $starting_bid );
		$this->add_placeholder( '{auction.bid_increment}', $auction?->get_bid_increment_formatted() );
		$this->add_placeholder( '{auction.bid_extension}', $auction?->get_bid_extension_formatted() );

		$high_bid = wc_price( $auction?->get_last_bid()?->get_subtotal() );
		$this->add_placeholder( '{auction.high_bid}', $high_bid );

		// Auction Stats.
		$this->add_placeholder( '{auction.total_raised}', $auction?->get_total_raised_formatted() );
		$this->add_placeholder( '{auction.bid_count}', $auction?->get_bid_count() );
		$this->add_placeholder( '{auction.goal}', $auction?->get_goal_formatted() );
		$this->add_placeholder( '{auction.estimated_value}', $auction?->get_estimated_value_formatted() );
		$this->add_placeholder( '{auction.expected_high_bid}', $auction?->get_expected_high_bid_formatted() );

		// User Specific Auction Details
		$user_last_bid        = $auction?->get_user_last_bid( $this->user_id );
		$user_last_bid_amount = $user_last_bid ? $user_last_bid->get_subtotal() : 0;
		$user_bid_count       = $this->user_id ? $auction?->get_user_bid_count( $this->user_id ) : '';
		$user_total_donated   = $this->user_id ? $auction?->get_user_total_donated_formatted( $this->user_id ) : '';
		$this->add_placeholder( '{auction.user.last_bid_amount}', wc_price( $user_last_bid_amount ) );
		$this->add_placeholder( '{auction.user.bid_count}', $user_bid_count );
		$this->add_placeholder( '{auction.user.total_donated}', $user_total_donated );

		// Reward Details.
		$this->add_placeholder( '{reward.title}', $reward?->get_title() );
		$this->add_placeholder( '{reward.purchase_note}', $reward?->get_purchase_note() );
		$this->add_placeholder( '{reward.claim_url}', goodbids()->rewards->get_claim_reward_url( $auction?->get_id() ) );
		$this->add_placeholder( '{reward.days_to_claim_setting}', goodbids()->get_config( 'auctions.reward-days-to-claim' ) );
		$this->add_placeholder( '{reward.claim_deadline_date}', goodbids()->rewards->get_claim_deadline_date( $auction?->get_id() ) );

		// General User Details.
		$referrer = new Referrer( $this->user_id );
		$this->add_placeholder( '{user.name}', $this->get_user_name() );
		$this->add_placeholder( '{user.account_url}', wc_get_page_permalink( 'myaccount' ) );
		$this->add_placeholder( '{user.free_bid_count}', goodbids()->free_bids->get_available_count( $this->user_id ) );
		$this->add_placeholder( '{user.free_bids_url}', goodbids()->free_bids->get_free_bids_url( $this->user_id ) );
		$this->add_placeholder( '{user.referral_link}', $referrer->get_link() );

		// Order Details
		$order_date = $order?->get_date_completed() ? $order->get_date_completed()->date( $datetime_format ) : '';
		$this->add_placeholder( '{order.total}', $order?->get_total() );
		$this->add_placeholder( '{order.date}', $order_date );

		// Free Bid Details
		$this->add_placeholder( '{free_bid.type}', $free_bid?->get_type_display() );
		$this->add_placeholder( '{free_bid.type_action}', $free_bid?->get_type_action() );

		// Request Details
		$support_admin_url = add_query_arg( 'post_type', goodbids()->support->get_post_type(), admin_url( 'edit.php' ) );
		$request_url       = get_edit_post_link( $request?->get_id() );
		$this->add_placeholder( '{support_request_admin_url}', $support_admin_url );
		$this->add_placeholder( '{request.url}', $request_url );
		$this->add_placeholder( '{request.user.name}', $request?->get_user()?->get_username() );
		$this->add_placeholder( '{request.user.email}', $request?->get_user()?->get_email() );
		$this->add_placeholder( '{request.type}', $request?->get_field( Request::FIELD_TYPE ) );
		$this->add_placeholder( '{request.nature}', $request?->get_field( Request::FIELD_NATURE ) );
		$this->add_placeholder( '{request.request}', $request?->get_field( Request::FIELD_REQUEST ) );
	}

	/**
	 * Set default Template Vars.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function default_vars(): void {
		$this->add_email_var( 'instance', $this );
		$this->add_email_var( 'email_heading', wp_strip_all_tags( $this->format_string( $this->get_heading() ) ) );
		$this->add_email_var( 'button_text', $this->get_button_text() );
		$this->add_email_var( 'button_url', $this->get_button_url() );
		$this->add_email_var( 'additional_content', $this->format_string( $this->get_additional_content() ) );
	}

	/**
	 * Set default Template Customizations.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function default_customizations(): void {
		add_action( 'woocommerce_email_header', [ $this, 'greeting_html' ], 12 );
		add_action( 'woocommerce_email_footer', [ $this, 'button_html' ], 5 );
		add_action( 'woocommerce_email_footer', [ $this, 'additional_content_html' ], 8 );
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

		// Set to User Email
		if ( $this->user_id ) {
			$user = get_user_by( 'ID', $this->user_id );
			if ( $user ) {
				$recipients[] = $user->user_email;
			}
		}

		$recipients = array_filter( $recipients, 'is_email' );

		if ( ! $recipients ) {
			return false;
		}

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

		if ( $this->is_super_admins_email() ) {
			$recipients[] = __( 'Super Admins', 'goodbids' );
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
	 * Check if the email is sent to Super Admins.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_super_admins_email(): bool {
		return $this->super_admins_email;
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
	 * @param mixed  $value
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
	 *
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
		$greeting = __( 'Hi', 'goodbids' );

		if ( ! $this->user_id ) {
			printf(
				'<p>%s,</p>',
				esc_html( $greeting )
			);
		} else {
			printf(
				'<p>%s %s,</p>',
				esc_html( $greeting ),
				esc_html( $this->get_user_name() )
			);
		}
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

		echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
	}

	/**
	 * Send the email to all Watchers
	 *
	 * @param Auction $auction
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function send_to_watchers( Auction $auction ): void {
		$watchers = goodbids()->watchers->get_watchers_by_auction( $auction->get_id() );
		foreach ( $watchers as $watcher ) {
			$user_id = goodbids()->watchers->get_user_id( $watcher );
			$this->trigger( $auction, $user_id );
		}
	}

	/**
	 * Send the email to all Bidders
	 *
	 * @param Auction $auction
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function send_to_bidders( Auction $auction ): void {
		$bidders = $auction->get_bidder_ids();
		foreach ( $bidders as $user_id ) {
			$this->trigger( $auction, $user_id );
		}
	}

	/**
	 * Send the email to all Admins
	 *
	 * @param mixed $object
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function send_to_admins( mixed $object ): void {
		$admins = get_users(
			[
				'role'   => 'administrator',
				'fields' => 'ID',
			]
		);

		if ( $this->is_super_admins_email() ) {
			$super_admins = get_super_admins();
			$super_ids    = [];
			foreach ( $super_admins as $username ) {
				$super_admin = get_user_by( 'login', $username );
				if ( ! $super_admin || ! $super_admin->user_email ) {
					continue;
				}
				$super_ids[] = $super_admin->ID;
			}
			$admins = array_merge( $admins, $super_ids );
		}

		$admins = array_unique( $admins );

		if ( empty( $admins ) ) {
			Log::error( 'No Admins found to send email to', [ 'object' => $object ] );
		}

		foreach ( $admins as $admin_id ) {
			$this->trigger( $object, $admin_id );
		}
	}

	/**
	 * Set common admin email vars
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function set_admin_vars(): void {
		$auction = $this->object instanceof Auction ? $this->object : null;
		$this->add_email_var( 'auction_estimated_value', $auction?->get_estimated_value() );
		$this->add_email_var( 'auction_estimated_value_formatted', $auction?->get_estimated_value_formatted() );
		$this->add_email_var( 'auction_goal', $auction?->get_goal() );
		$this->add_email_var( 'auction_goal_formatted', $auction?->get_goal_formatted() );
		$this->add_email_var( 'auction_expected_high_bid', $auction?->get_expected_high_bid() );
		$this->add_email_var( 'auction_expected_high_bid_formatted', $auction?->get_expected_high_bid_formatted() );
	}
}
