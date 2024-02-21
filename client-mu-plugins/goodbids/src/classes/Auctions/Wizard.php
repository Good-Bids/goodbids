<?php
/**
 * Auction Creation Wizard
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

/**
 * Auction Wizard Class
 *
 * @since 1.0.0
 */
class Wizard {

	const BASE_URL = 'edit.php?post_type=';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-auction-wizard';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Init Admin Menu Page.
		$this->init_admin_page();

		// Localize Variables
		$this->localize_variables();
	}

	/**
	 * Initialize admin menu page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_admin_page(): void {
		add_action(
			'admin_menu',
			function(): void {
				add_submenu_page(
					self::BASE_URL . goodbids()->auctions->get_post_type(),
					esc_html__( 'Auction Wizard', 'goodbids' ),
					esc_html__( 'Auction Wizard', 'goodbids' ),
					'create_auctions',
					self::PAGE_SLUG,
					[ $this, 'wizard_admin_page' ],
					5
				);
			}
		);
	}

	/**
	 * Auction Wizard Admin Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function wizard_admin_page(): void {
		$step = sanitize_text_field( filter_input( INPUT_GET, 'step', FILTER_SANITIZE_ENCODED ) );

		$slug = match ( $step ) {
			'product' => 'product',
			'auction' => 'auction',
			'finish'  => 'finish',
			default   => 'start',
		};

		$data = [
			'namespace' => self::PAGE_SLUG,
			'slug'      => $slug,
		];

		goodbids()->load_view( 'admin/auctions/wizard.php', $data );
	}

	/**
	 * Get the URL for the wizard
	 *
	 * @since 1.0.0
	 *
	 * @param string $step
	 *
	 * @return string
	 */
	private function get_url( string $step = '' ): string {
		return admin_url( self::BASE_URL . goodbids()->auctions->get_post_type() . '&page=' . self::PAGE_SLUG . '&step=' . $step );
	}

	/**
	 * Set some variables for JS
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function localize_variables(): void {
		add_action(
			'goodbids_enqueue_admin_scripts',
			function( string $js_handle ): void {
				$js_vars = [
					// Misc.
					'baseURL' => $this->get_url(),

					// WP Variables.
					'rewardCategorySlug' => Rewards::ITEM_TYPE,

					'strings' => [
						// Start Page.
						'introHeading' => __( 'Build an Auction!', 'goodbids' ),
						'introText'    => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'goodbids' ),
						'introButtonText' => __( 'Let\'s get started', 'goodbids' ),

						// Product Page.
						'productTips' => __( 'You can upload multiple images for your product. The "Product Image" image will be used as the main product image.', 'goodbids' ),

						'createRewardHeading'    => __( 'Create Auction Reward', 'goodbids' ),
						'createRewardSubheading' => __( 'What are you auctioning?', 'goodbids' ),

						'productTitle'         => __( 'Title', 'goodbids' ),
						'productTitleTooltip'  => __( 'Product title.', 'goodbids' ),
						'productTitleRequired' => __( 'Title is required', 'goodbids' ),

						'fairMarketValueLabel'    => __( 'Fair Market Value', 'goodbids' ),
						'fairMarketValueTooltip'  => __( 'The fair market value of your reward', 'goodbids' ),
						'fairMarketValueRequired' => __( 'Fair Market Value is required', 'goodbids' ),

						'productImageLabel'   => __( 'Product Image', 'goodbids' ),
						'productImageTooltip' => __( 'Select a single image as the focal image of your product.', 'goodbids' ),
						'imageUploadSingle'   => __( 'Click to upload', 'goodbids' ),

						'productGalleryLabel'   => __( 'Product Gallery', 'goodbids' ),
						'productGalleryTooltip' => __( 'Select additional images for your product gallery.', 'goodbids' ),
						'imageUploadMultiple'   => __( 'Click to upload multiple', 'goodbids' ),

						'productTypeLabel'      => __( 'What type of product is it?', 'goodbids' ),
						'productTypePhysical'   => __( 'Physical', 'goodbids' ),
						'productTypeDigital'    => __( 'Digital', 'goodbids' ),
						'productTypeExperience' => __( 'Experience', 'goodbids' ),

						// Dimensions
						'productWeightLabel'   => __( 'Weight (lbs)', 'goodbids' ),
						'productWeightTooltip' => __( 'Product weight in lbs.', 'goodbids' ),
						'productLengthLabel'   => __( 'Length (in)', 'goodbids' ),
						'productLengthTooltip' => __( 'Product length in inches', 'goodbids' ),
						'productWidthLabel'    => __( 'Width (in)', 'goodbids' ),
						'productWidthTooltip'  => __( 'Product width in inches', 'goodbids' ),
						'productHeightLabel'   => __( 'Height (in)', 'goodbids' ),
						'productHeightTooltip' => __( 'Product height in inches', 'goodbids' ),

						'purchaseNoteLabel'   => __( 'Redemption Details for Auction Winner', 'goodbids' ),
						'purchaseNoteTooltip' => __( 'Instructions for the auction winner to redeem their reward', 'goodbids' ),

						'shippingClassLabel'   => __( 'Shipping Class', 'goodbids' ),
						'shippingClassTooltip' => __( 'Determines base shipping cost', 'goodbids' ),
						'shippingClassNone'    => __( 'No Shipping Class', 'goodbids' ),

						// Validation
						'invalidDecimal' => __( 'Invalid value. Must match format 0.00', 'goodbids' ),
						'nextButtonText' => __( 'Save and Continue', 'goodbids' ),
					],
				];

				wp_localize_script( $js_handle, 'gbAuctionWizard', $js_vars );
			}
		);
	}
}
