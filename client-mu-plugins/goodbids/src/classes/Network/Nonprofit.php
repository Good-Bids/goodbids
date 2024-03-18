<?php
/**
 * GoodBids Network Nonprofit
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Auctions\Auction;
use GoodBids\Nonprofits\Invoice;
use GoodBids\Nonprofits\Stripe;
use GoodBids\Nonprofits\Verification;
use stdClass;
use WP_Site;

/**
 * Network Admin Nonprofits Class
 *
 * @since 1.0.0
 */
class Nonprofit {

	/**
	 * @since 1.0.0
	 */
	const STATUS_PENDING = 'pending';

	/**
	 * @since 1.0.0
	 */
	const STATUS_LIVE = 'live';

	/**
	 * @since 1.0.0
	 */
	const STATUS_INACTIVE = 'inactive';

	/**
	 * @since 1.0.0
	 * @var int
	 */
	private int $site_id;

	/**
	 * @since 1.0.0
	 * @var ?WP_Site
	 */
	private ?WP_Site $site;

	/**
	 * @since 1.0.0
	 */
	public function __construct( int $site_id ) {
		$this->site_id = $site_id;
		$this->site    = get_site( $this->get_id() );
	}

	/**
	 * Get the Site ID
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_id(): int {
		return $this->site_id;
	}

	/**
	 * Check to see if the site is valid.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_valid(): bool {
		return ! is_null( $this->site );
	}

	/**
	 * Get the Site URL
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_url(): string {
		return get_site_url( $this->get_id() );
	}

	/**
	 * Get the Site Edit Network Admin URL
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_edit_url(): string {
		$url = network_admin_url( 'sites.php' );
		return add_query_arg( 'id', $this->get_id(), $url );
	}

	/**
	 * Get Nonprofit Site Name
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_name(): string {
		return goodbids()->sites->swap(
			function () {
				return get_bloginfo( 'name' );
			},
			$this->get_id()
		);
	}

	/**
	 * Get Nonprofit Email Address for Stripe
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_email_for_stripe(): string {
		if ( $this->get_finance_contact_email() ) {
			return $this->get_finance_contact_email();
		}

		if ( $this->get_primary_contact_email() ) {
			return $this->get_primary_contact_email();
		}

		return $this->get_admin_email();
	}

	/**
	 * Get the Nonprofit Site status
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_status(): string {
		return goodbids()->verification->get_nonprofit_data( $this->get_id(), 'status' );
	}

	/**
	 * Update the Site's Status
	 *
	 * @since 1.0.0
	 *
	 * @param string $status
	 *
	 * @return bool|int
	 */
	public function set_status( string $status ): bool|int {
		if ( ! in_array( $status, goodbids()->network->nonprofits->get_site_status_options(), true ) ) {
			return false;
		}

		// Onboarding must be complete.
		if ( Nonprofit::STATUS_LIVE === $status && ! $this->is_onboarded() ) {
			return false;
		}

		$meta_key = Verification::OPTION_SLUG . '-' . Verification::STATUS_OPTION;

		return update_site_meta( $this->get_id(), $meta_key, $status );
	}

	/**
	 * Get Nonprofit Site Standing
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_standing(): string {
		return goodbids()->sites->swap(
			function () {
				if ( goodbids()->invoices->has_overdue_invoices() ) {
					return __( 'Delinquent', 'goodbids' );
				}

				return __( 'Good', 'goodbids' );
			},
			$this->get_id()
		);
	}

	/**
	 * Get Nonprofit Total Auctions
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_total_auctions(): int {
		return goodbids()->sites->swap(
			fn () => goodbids()->auctions->get_all()->found_posts,
			$this->get_id()
		);
	}

	/**
	 * Get Nonprofit Total Raised
	 *
	 * @since 1.0.0
	 *
	 * @return float
	 */
	public function get_total_raised(): float {
		return goodbids()->sites->swap(
			function () {
				$auctions = goodbids()->auctions->get_all();
				$total    = 0;

				while ( $auctions->have_posts() ) :
					$auctions->the_post();
					$auction = new Auction( get_the_ID() );
					$total  += $auction->get_total_raised();
				endwhile;
				wp_reset_postdata();

				return $total;
			},
			$this->get_id()
		);
	}

	/**
	 * Get Nonprofit Site Revenue
	 *
	 * @since 1.0.0
	 *
	 * @return float
	 */
	public function get_total_revenue(): float {
		return goodbids()->sites->swap(
			function () {
				$invoices = goodbids()->invoices->get_all_ids();
				$total    = 0;

				while ( $invoices->have_posts() ) :
					$invoices->the_post();
					$invoice = new Invoice( get_the_ID() );
					$total  += $invoice->get_amount();
				endwhile;
				wp_reset_postdata();

				return $total;
			},
			$this->get_id()
		);
	}

	/**
	 * Get Nonprofit Site Admin URL
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_admin_url(): string {
		return goodbids()->sites->swap(
			function () {
				return admin_url();
			},
			$this->get_id()
		);
	}

	/**
	 * Get Nonprofit Site Admin Email
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_admin_email(): string {
		return get_blog_option(  $this->get_id(), 'admin_email', '' );
	}

	/**
	 * Get Nonprofit Site Age
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_age(): string {
		return human_time_diff( strtotime( $this->site->registered, time() ) );
	}

	/**
	 * Get the site Registered Date
	 *
	 * @since 1.0.0
	 *
	 * @param string $format
	 *
	 * @return string
	 */
	public function get_registered_date( string $format = 'n/j/Y' ): string {
		return mysql2date( $format, $this->site->registered );
	}

	/**
	 * Is the Nonprofit Site Verified?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_verified(): bool {
		return goodbids()->verification->is_verified( $this->get_id() );
	}

	/**
	 * Get the primary contact email for this Nonprofit
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_primary_contact_email(): ?string {
		return goodbids()->verification->get_nonprofit_data( $this->get_id(), 'primary_contact_email' );
	}

	/**
	 * Get the finance contact email for this Nonprofit
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_finance_contact_email(): ?string {
		return goodbids()->verification->get_nonprofit_data( $this->get_id(), 'finance_contact_email' );
	}

	/**
	 * Update the Stripe Customer Email if it doesn't match the current email
	 *
	 * @since 1.0.0
	 *
	 * @param string $email
	 *
	 * @return void
	 */
	public function update_stripe_email( string $email ): void {
		$stripe_customer_id = $this->get_stripe_customer_id();
		$stripe_customer    = $this->get_stripe_customer();

		if ( ! $email || ! $stripe_customer || $stripe_customer->email === $email ) {
			return;
		}

		goodbids()->invoices->stripe->update_customer( compact( 'email' ), $stripe_customer_id );
	}

	/**
	 * Get the Nonprofit's Stripe Customer ID
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_stripe_customer_id(): ?string {
		return get_blog_option( $this->get_id(), Stripe::STRIPE_CUSTOMER_ID_OPT, null );
	}

	/**
	 * Get the Nonprofit's Stripe Customer
	 *
	 * @since 1.0.0
	 *
	 * @return ?stdClass
	 */
	public function get_stripe_customer(): ?stdClass {
		$customer_id = $this->get_stripe_customer_id();

		if ( ! $customer_id ) {
			return null;
		}

		return goodbids()->invoices->stripe->get_customer( $customer_id );
	}

	/**
	 * Check if Nonprofit has completed onboarding.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_onboarded(): bool {
		return goodbids()->network->nonprofits->is_onboarded( $this->get_id() );
	}
}
