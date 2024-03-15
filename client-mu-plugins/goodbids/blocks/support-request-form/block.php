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
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE_BID = 'Placed Bid';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE_REWARD = 'Claimed Reward';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE_AUCTION = 'Auction';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE_OTHER = 'Other';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FIELD_TYPE = '_type';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FIELD_AUCTION_ID = '_auction_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FIELD_BID_ID = '_bid_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FIELD_REWARD_ID = '_reward_id';

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

		// Populate the Select Menus.
		$this->insert_auction_options();
		$this->insert_bid_options();
		$this->insert_reward_options();

		// Field Dependencies
		$this->modify_for_dependencies();

		// Generate the fields.
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
				self::FIELD_TYPE => [
					'type'     => 'select',
					'label'    => __( 'What do you need help with?', 'goodbids' ),
					'required' => true,
					'options'  => [
						self::TYPE_BID     => __( 'A bid I placed', 'goodbids' ),
						self::TYPE_REWARD  => __( 'A reward I claimed', 'goodbids' ),
						self::TYPE_AUCTION => __( 'An auction', 'goodbids' ),
						self::TYPE_OTHER   => __( 'Something else', 'goodbids' ),
					],
				],
				self::FIELD_AUCTION_ID => [
					'type'    => 'select',
					'label'   => __( 'Which Auction are you referencing?', 'goodbids' ),
					'options' => [
						'Auction 1',
						'Auction 2',
						'Auction 3',
					],
					'dependencies' => [
						self::FIELD_TYPE => [ self::TYPE_BID, self::TYPE_REWARD, self::TYPE_AUCTION ],
					],
				],
				self::FIELD_BID_ID => [
					'type'    => 'select',
					'label'   => __( 'Which bid are you referencing?', 'goodbids' ),
					'options' => [
						'Bid 1',
						'Bid 2',
						'Bid 3',
					],
					'dependencies' => [
						self::FIELD_TYPE       => self::TYPE_BID,
						self::FIELD_AUCTION_ID => null,
					],
				],
				self::FIELD_REWARD_ID => [
					'type'    => 'select',
					'label'   => __( 'Which reward are you referencing?', 'goodbids' ),
					'options' => [
						'Reward 1',
						'Reward 2',
						'Reward 3',
					],
					'dependencies' => [
						self::FIELD_TYPE       => self::TYPE_REWARD,
						self::FIELD_AUCTION_ID => null,
					],
				],
				'request_nature' => [
					'type'    => 'select',
					'label'   => __( 'What is the nature of your request?', 'goodbids' ),
					'options' => [
						[
							'label' => __( 'Report an issue', 'goodbids' ),
							'value' => __( 'Issue', 'goodbids' ),
						],
						[
							'label' => __( 'Request a refund', 'goodbids' ),
							'value' => __( 'Refund', 'goodbids' ),
							'dependencies' => [
								self::FIELD_TYPE => self::TYPE_BID,
							],
						],
						[
							'label' => __( 'Ask a question', 'goodbids' ),
							'value' => __( 'Question', 'goodbids' ),
						],
					],
					'dependencies' => [
						self::FIELD_TYPE => null,
					],
				],
				'request_description' => [
					'type'        => 'textarea',
					'label'       => __( 'Please describe your request', 'goodbids' ),
					'placeholder' => __( 'Tell us what\'s going on', 'goodbids' ),
					'dependencies' => [
						self::FIELD_TYPE => null,
					],
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
			'auction' => self::FIELD_AUCTION_ID,
			'bid'     => self::FIELD_BID_ID,
			'reward'  => self::FIELD_REWARD_ID,
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

				$options = collect( $options )
					->sortBy( 'label' )
					->all();

				$fields[ self::FIELD_AUCTION_ID ]['options'] = $options;

				return $fields;
			}
		);
	}

	/**
	 * Populate the Bids Select with the user's bids
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function insert_bid_options(): void {
		add_filter(
			'goodbids_support_request_form_fields',
			function ( array $fields ): array {
				if ( ! is_user_logged_in() || is_admin() ) {
					return $fields;
				}

				$form_data = $this->get_form_data();
				$options   = [];

				if ( empty( $form_data[ self::FIELD_AUCTION_ID ] ) ) {
					$fields[ self::FIELD_BID_ID ]['options'] = [
						[
							'value' => '',
							'label' => __( 'Select an Auction first', 'goodbids' ),
						],
					];
					return $fields;
				}

				$bids = goodbids()->sites->get_user_bid_orders( get_current_user_id() );
				$bids = collect( $bids )
					->filter(
						function ( $bid_data ) use ( $form_data ) {
							return goodbids()->sites->swap(
								function () use ( $bid_data, $form_data ) {
									return goodbids()->woocommerce->orders->get_auction_id( $bid_data['order_id'] ) === $form_data[ self::FIELD_AUCTION_ID ];
								},
								$bid_data['site_id']
							);
						}
					)
					->all();

				foreach ( $bids as $bid_data ) {
					goodbids()->sites->swap(
						function () use ( $bid_data, &$options ) {
							$options[] = [
								'value' => $bid_data['site_id'] . '|' . $bid_data['order_id'],
								'label' => get_the_title( $bid_data['order_id'] ),
							];
						},
						$bid_data['site_id']
					);
				}

				$fields[ self::FIELD_BID_ID ]['options'] = $options;

				return $fields;
			}
		);
	}

	/**
	 * Populate the Rewards Select with the user's bids
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function insert_reward_options(): void {
		add_filter(
			'goodbids_support_request_form_fields',
			function ( array $fields ): array {
				if ( ! is_user_logged_in() || is_admin() ) {
					return $fields;
				}

				$form_data = $this->get_form_data();
				$options   = [];

				if ( empty( $form_data[ self::FIELD_AUCTION_ID ] ) ) {
					$fields[ self::FIELD_REWARD_ID ]['options'] = [
						[
							'value' => '',
							'label' => __( 'Select an Auction first', 'goodbids' ),
						],
					];
					return $fields;
				}

				$rewards = goodbids()->sites->get_user_reward_orders( get_current_user_id() );
				$rewards = collect( $rewards )
					->filter(
						function ( $reward_data ) use ( $form_data ) {
							return goodbids()->sites->swap(
								function () use ( $reward_data, $form_data ) {
									return goodbids()->woocommerce->orders->get_auction_id( $reward_data['order_id'] ) === $form_data[ self::FIELD_AUCTION_ID ];
								},
								$reward_data['site_id']
							);
						}
					)
					->all();

				foreach ( $rewards as $reward_data ) {
					goodbids()->sites->swap(
						function () use ( $reward_data, &$options ) {
							$options[] = [
								'value' => $reward_data['site_id'] . '|' . $reward_data['order_id'],
								'label' => get_the_title( $reward_data['order_id'] ),
							];
						},
						$reward_data['site_id']
					);
				}

				$fields[ self::FIELD_REWARD_ID ]['options'] = $options;

				return $fields;
			}
		);
	}

	/**
	 * Update fields with dependencies
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function modify_for_dependencies(): void {
		add_filter(
			'goodbids_support_request_form_fields',
			function ( array $fields ): array {
				return $this->handle_dependencies( $fields );
			}
		);
	}

	/**
	 * Hide fields based on dependencies
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	private function handle_dependencies( array $fields ): array {
		$form_data = $this->get_form_data();

		foreach ( $fields as $key => &$field ) {
			if ( ! empty( $field['options'] ) ) {
				$field['options'] = $this->handle_dependencies( $field['options'] );
			}

			if ( empty( $field['dependencies'] ) ) {
				continue;
			}

			$dependencies = $field['dependencies'];

			foreach ( $dependencies as $dependency_key => $dependency_value ) {
				if ( empty( $form_data[ $dependency_key ] ) ) {
					$fields[ $key ]['hidden'] = true;
					break;
				}

				if ( is_null( $dependency_value ) ) {
					continue;
				}

				if ( is_string( $dependency_value ) && $form_data[ $dependency_key ] === $dependency_value ) {
					continue;
				}

				if ( is_array( $dependency_value ) && in_array( $form_data[ $dependency_key ], $dependency_value, true ) ) {
					continue;
				}

				$fields[ $key ]['hidden'] = true;
				break;
			}
		}

		return $fields;
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
