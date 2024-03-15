<?php
/**
 * Support Request Form Block
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Blocks;

use GoodBids\Plugins\ACF\ACFBlock;
use GoodBids\Utilities\Log;

/**
 * Class for Support Request Form Block
 *
 * @since 1.0.0
 */
class SupportRequestForm extends ACFBlock {

	/**
	 * Nonce action
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const NONCE_ACTION = 'support-request-form';

	/**
	 * Form fields
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private array $fields = [];

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param array $block
	 */
	public function __construct( array $block ) {
		parent::__construct( $block );

		$this->insert_auction_options();
		$this->init_fields();
	}

	/**
	 * Initialize the form fields
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_fields(): void {
		$this->fields = apply_filters(
			'goodbids_support_request_form_fields',
			[
				'request_type' => [
					'type'    => 'select',
					'label'   => __( 'What do you need help with?', 'goodbids' ),
					'options' => [
						__( 'A bid I placed', 'goodbids' ),
						__( 'A reward I claimed', 'goodbids' ),
						__( 'An auction', 'goodbids' ),
						__( 'Something else', 'goodbids' ),
					],
				],
				'auction_id' => [
					'type'    => 'select',
					'label'   => __( 'Which Auction are you referencing?', 'goodbids' ),
					'options' => [
						'Auction 1',
						'Auction 2',
						'Auction 3',
					],
				],
				'bid_id' => [
					'type'    => 'select',
					'label'   => __( 'Which bid are you referencing?', 'goodbids' ),
					'options' => [
						'Bid 1',
						'Bid 2',
						'Bid 3',
					],
				],
				'reward_id' => [
					'type'    => 'select',
					'label'   => __( 'Which reward are you referencing?', 'goodbids' ),
					'options' => [
						'Reward 1',
						'Reward 2',
						'Reward 3',
					],
				],
				'request_nature' => [
					'type'    => 'select',
					'label'   => __( 'What is the nature of your request?', 'goodbids' ),
					'options' => [
						__( 'Report an issue', 'goodbids' ),
						__( 'Request a refund', 'goodbids' ),
						__( 'Ask a question', 'goodbids' ),
					],
				],
				'request_description' => [
					'type'  => 'textarea',
					'label' => __( 'Please describe your request', 'goodbids' ),
				],
			]
		);
	}

	/**
	 * Render the form
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render(): void {
		$form_data    = $this->get_form_data();
		$button_class = 'text-gb-green-700 bg-gb-green-100 border-0 hover:bg-gb-green-500 hover:text-white focus:outline-none font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 cursor-pointer';

		if ( is_admin() ) {
			$button_class .= ' pointer-events-none';
		}
		?>
		<form method="post" action="" class="bg-gb-green-700 rounded p-4">
			<?php
			if ( ! is_admin() ) {
				wp_nonce_field( self::NONCE_ACTION, self::NONCE_ACTION . '-nonce' );
			}

			$this->inner_blocks();

			foreach ( $this->fields as $key => $field ) :
				goodbids()->forms->render_field( $key, $field, '', $form_data );
			endforeach;
			?>

			<input
				type="submit"
				value="<?php esc_attr_e( 'Submit Request', 'goodbids' ); ?>"
				class="<?php echo esc_attr( $button_class ); ?>"
			>
		</form>
		<?php
	}

	/**
	 * Grab the posted form data.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_form_data(): array {
		$form_data = $this->get_url_vars();

		if ( empty( $_POST[ self::NONCE_ACTION . '-nonce' ] ) ) {
			return $form_data;
		}

		if ( ! wp_verify_nonce( self::NONCE_ACTION . '-nonce', self::NONCE_ACTION ) ) {
			Log::debug( 'Failed to verify nonce.' );
			return $form_data;
		}

		foreach ( $this->fields as $key => $field ) {
			$form_data[ $key ] = ! empty( $_POST[ $key ] ) ? sanitize_text_field( $_POST[ $key ] ) : '';
		}

		return $form_data;
	}

	/**
	 * Get pre-populated field values from URL.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_url_vars(): array {
		$form_data = [];
		$query_vars = [
			'auction' => 'auction_id',
			'bid'     => 'bid_id',
			'reward'  => 'reward_id',
		];

		foreach ( $query_vars as $query_var => $field_key ) {
			if ( ! empty( $_GET[ $query_var ] ) ) { // phpcs:ignore
				$form_data[ $field_key ] = sanitize_text_field( $_GET[ $query_var ] ); // phpcs:ignore
			}
		}

		return $form_data;
	}

	/**
	 * Populate the Auctions Select with the user's auctions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function insert_auction_options(): void {
		add_filter(
			'goodbids_support_request_form_fields',
			function ( array $fields ): array {
				if ( ! is_user_logged_in() || is_admin() ) {
					return $fields;
				}

				$options  = [];
				$auctions = goodbids()->sites->get_user_participating_auctions();

				foreach ( $auctions as $auction ) {
					goodbids()->sites->swap(
						function () use ( $auction, &$options ) {
							$options[] = [
								'value' => $auction['site_id'] . '|' . $auction['auction_id'],
								'label' => get_the_title( $auction['auction_id'] ),
							];
						},
						$auction['site_id']
					);
				}

				$fields['auction_id']['options'] = $options;

				return $fields;
			}
		);
	}

	/**
	 * Render the Inner Blocks tag
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function inner_blocks(): void {
		$template = [
			[
				'core/post-title',
				[
					'textColor' => 'base',
				],
			],
			[
				'core/paragraph',
				[
					'content'   => __( 'Use the form below to submit a support request to this Nonprofit. Your submission will be visible to administrators for this Nonprofit site as well as the GOODBIDS support team. We will respond as soon as we can.', 'goodbids' ),
					'textColor' => 'base',
				],
			],
		];

		printf(
			'<InnerBlocks template="%s" />',
			esc_attr( wp_json_encode( $template ) )
		);
	}
}
