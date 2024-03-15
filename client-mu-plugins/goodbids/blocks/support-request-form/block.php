<?php
/**
 * Support Request Form Block
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Blocks;

use GoodBids\Plugins\ACF\ACFBlock;
use GoodBids\Users\Bidder;
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
	const TYPE_BID = 'Bid';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE_REWARD = 'Reward';

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
	 * Error message
	 *
	 * @since 1.0.0
	 *
	 * @var ?string
	 */
	private ?string $error = null;

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

		// Handle the Form Submission.
		$this->handle_submission();
	}

	/**
	 * Get the Current URL with Query Parameters added.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_current_url(): string {
		global $wp;
		$current_url   = home_url( $wp->request );
		$form_data     = $this->get_form_data();
		$append_fields = [
			self::FIELD_TYPE,
			self::FIELD_AUCTION_ID,
			self::FIELD_BID_ID,
			self::FIELD_REWARD_ID,
		];

		foreach ( $append_fields as $data_field ) {
			if ( ! empty( $form_data[ $data_field ] ) ) {
				$current_url = add_query_arg( $data_field, urlencode( $form_data[ $data_field ] ), $current_url );
			}
		}

		return $current_url;
	}

	/**
	 * Initialize the form fields
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_fields(): void {
		$current_url = $this->get_current_url();
		$form_data   = $this->get_form_data();

		// Dynamic Dependencies for the 2 Request Fields
		$extra_deps  = [
			self::FIELD_TYPE => null,
		];

		$type = ! empty( $form_data[ self::FIELD_TYPE ] ) ? $form_data[ self::FIELD_TYPE ] : null;

		if ( in_array( $type, [ self::TYPE_BID, self::TYPE_REWARD ], true ) ) {
			$extra_deps[ self::FIELD_AUCTION_ID ] = null;

			if ( $type === self::TYPE_BID ) {
				$extra_deps[ self::FIELD_BID_ID ] = null;
			} elseif ( $type === self::TYPE_REWARD ) {
				$extra_deps[ self::FIELD_REWARD_ID ] = null;
			}
		}

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
					'attr'    => [
						'hx-trigger'   => 'change',
						'hx-get'       => $current_url,
						'hx-target'    => '#gb-support-form-target',
						'hx-select'    => '#gb-support-form-target',
						'hx-indicator' => '[data-form-spinner]',
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
					'attr'    => [
						'hx-trigger'   => 'change',
						'hx-get'       => $current_url,
						'hx-target'    => '#gb-support-form-target',
						'hx-select'    => '#gb-support-form-target',
						'hx-indicator' => '[data-form-spinner]',
					],
					'required'     => 'dependencies',
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
					'required'     => 'dependencies',
					'dependencies' => [
						self::FIELD_TYPE       => self::TYPE_BID,
						self::FIELD_AUCTION_ID => null,
					],
					'attr'    => [
						'hx-trigger'   => 'change',
						'hx-get'       => $current_url,
						'hx-target'    => '#gb-support-form-target',
						'hx-select'    => '#gb-support-form-target',
						'hx-indicator' => '[data-form-spinner]',
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
					'required'     => 'dependencies',
					'dependencies' => [
						self::FIELD_TYPE       => self::TYPE_REWARD,
						self::FIELD_AUCTION_ID => null,
					],
					'attr'    => [
						'hx-trigger'   => 'change',
						'hx-get'       => $current_url,
						'hx-target'    => '#gb-support-form-target',
						'hx-select'    => '#gb-support-form-target',
						'hx-indicator' => '[data-form-spinner]',
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
					'required'     => 'dependencies',
					'dependencies' => $extra_deps,
				],
				'request_description' => [
					'type'         => 'textarea',
					'label'        => __( 'Please describe your request', 'goodbids' ),
					'placeholder'  => __( 'Tell us what\'s going on', 'goodbids' ),
					'required'     => 'dependencies',
					'dependencies' => $extra_deps,
				],
			]
		);
	}

	/**
	 * Check if submission was processed.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function submission_processed(): bool {
		return ! empty( $_GET['success'] ); // phpcs:ignore
	}

	/**
	 * Render the success message
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function render_success(): void {
		?>
		<div class="bg-gb-green-700 rounded p-4">
			<p class="text-gb-green-100">
				<?php esc_html_e( 'Your request has been submitted. We will respond as soon as we can.', 'goodbids' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Check all required fields.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function passed_validation(): bool {
		$form_data = $this->get_form_data();

		foreach ( $this->fields as $key => $field ) {
			if ( empty( $field['required'] ) ) {
				continue;
			}

			if ( 'dependencies' === $field['required'] && ! empty( $field['dependencies'] ) ) {
				$required = array_keys( $field['dependencies'] );
			} elseif ( is_array( $field['required'] ) ) {
				$required = $field['required'];
			} else {
				$required = [ $key ];
			}

			foreach ( $required as $item ) {
				if ( ! empty( $form_data[ $item ] ) ) {
					continue;
				}

				// Check for 0 values.
				if ( in_array( $form_data[ $item ], [ '0', 0 ], true ) ) {
					continue;
				}

				$this->error = sprintf(
					/* translators: %s: Field Label */
					__( 'The %s field is required.', 'goodbids' ),
					$field['label']
				);

				return false;
			}
		}

		return true;
	}

	/**
	 * Render the form
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render(): void {
		if ( $this->submission_processed() ) {
			$this->render_success();
			return;
		}

		$form_data    = $this->get_form_data();
		$button_class = 'text-gb-green-700 bg-gb-green-100 border-0 hover:bg-gb-green-500 hover:text-white focus:outline-none font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 cursor-pointer';

		if ( is_admin() ) {
			$button_class .= ' pointer-events-none';
		}
		?>
		<form data-form-spinner method="post" action="" class="bg-gb-green-700 rounded p-4 relative group opacity-100 group-[.htmx-request]:opacity-50 transition-opacity">
			<div class="absolute flex justify-center w-full -translate-y-full">
				<svg xmlns="http://www.w3.org/2000/svg" class="relative inset-0 htmx-indicator w-14 h-14 animate-spin" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3C16.9706 3 21 7.02944 21 12H19C19 8.13401 15.866 5 12 5V3Z"></path></svg>
			</div>
			<?php
			if ( ! is_admin() ) {
				wp_nonce_field( self::NONCE_ACTION, self::NONCE_ACTION . '-nonce' );
			}

			$this->inner_blocks();

			?>
			<div id="gb-support-form-target">
				<?php
				$this->render_error();

				foreach ( $this->fields as $key => $field ) :
					goodbids()->forms->render_field( $key, $field, '', $form_data );
				endforeach;
				?>
				<input
					type="submit"
					value="<?php esc_attr_e( 'Submit Request', 'goodbids' ); ?>"
					class="<?php echo esc_attr( $button_class ); ?>"
				>
			</div>
		</form>
		<?php
	}

	/**
	 * Render the error message if exists.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function render_error(): void {
		if ( ! $this->error ) {
			return;
		}
		?>
		<div class="bg-gb-red-700 rounded p-4 mb-4">
			<p class="text-gb-red-100">
				<?php echo esc_html( $this->error ); ?>
			</p>
		</div>
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
			'type'    => self::FIELD_TYPE,
			'auction' => self::FIELD_AUCTION_ID,
			'bid'     => self::FIELD_BID_ID,
			'reward'  => self::FIELD_REWARD_ID,
		];

		// Check both values for data.
		foreach ( $query_vars as $query_var => $field_key ) {
			if ( ! empty( $_GET[ $query_var ] ) ) { // phpcs:ignore
				$form_data[ $field_key ] = sanitize_text_field( $_GET[ $query_var ] ); // phpcs:ignore
			}
			if ( ! empty( $_GET[ $field_key ] ) ) { // phpcs:ignore
				$form_data[ $field_key ] = sanitize_text_field( $_GET[ $field_key ] ); // phpcs:ignore
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

				if ( empty( $options ) ) {
					$fields[ self::FIELD_AUCTION_ID ]['options'] = [
						[
							'value' => 'unknown',
							'label' => __( 'No auctions found. Select this to submit your request anyway.', 'goodbids' ),
						],
					];
					return $fields;
				}

				$options = collect( $options )
					->sortBy( 'label' )
					->values()
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

				list( $site_id, $auction_id ) = array_map( 'intval', explode( '|', $form_data[ self::FIELD_AUCTION_ID ] ) );
				$bids = goodbids()->sites->get_user_bid_orders( get_current_user_id() );
				$bids = collect( $bids )
					->filter(
						fn ( $bid_data ) => $bid_data['site_id'] === $site_id
					)
					->filter(
						function ( $bid_data ) use ( $auction_id ) {
							return goodbids()->sites->swap(
								function () use ( $bid_data, $auction_id ) {
									return goodbids()->woocommerce->orders->get_auction_id( $bid_data['order_id'] ) === $auction_id;
								},
								$bid_data['site_id']
							);
						}
					)
					->values()
					->all();

				if ( empty( $bids ) ) {
					$fields[ self::FIELD_BID_ID ]['options'] = [
						[
							'value' => '',
							'label' => __( 'No bids found. Select this to submit your request anyway.', 'goodbids' ),
						],
					];
					return $fields;
				}

				foreach ( $bids as $bid_data ) {
					goodbids()->sites->swap(
						function () use ( $bid_data, &$options ) {
							$order  = wc_get_order( $bid_data['order_id'] );
							$title  = __( 'Bid Order #', 'goodbids' ) . $bid_data['order_id'];
							$title .= $bid_data['order_id'];
							$title .= ' (' . wp_strip_all_tags( wc_price( $order->get_total( 'edit' ) ) ) . ')';

							$options[] = [
								'value' => $bid_data['site_id'] . '|' . $bid_data['order_id'],
								'label' => $title,
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

				list( $site_id, $auction_id ) = array_map( 'intval', explode( '|', $form_data[ self::FIELD_AUCTION_ID ] ) );
				$rewards = goodbids()->sites->get_user_reward_orders( get_current_user_id() );
				$rewards = collect( $rewards )
					->filter(
						fn ( $reward_data ) => $reward_data['site_id'] === $site_id
					)
					->filter(
						function ( $reward_data ) use ( $auction_id ) {
							return goodbids()->sites->swap(
								function () use ( $reward_data, $auction_id ) {
									return goodbids()->woocommerce->orders->get_auction_id( $reward_data['order_id'] ) === $auction_id;
								},
								$reward_data['site_id']
							);
						}
					)
					->values()
					->all();

				if ( empty( $rewards ) ) {
					$fields[ self::FIELD_REWARD_ID ]['options'] = [
						[
							'value' => 'unknown',
							'label' => __( 'No rewards found. Select this to submit your request anyway.', 'goodbids' ),
						],
					];
					return $fields;
				}

				foreach ( $rewards as $reward_data ) {
					goodbids()->sites->swap(
						function () use ( $reward_data, &$options ) {
							$order = wc_get_order( $reward_data['order_id'] );
							$title = __( 'Reward Order #', 'goodbids' ) . $reward_data['order_id'];
							foreach ( $order->get_items() as $item ) {
								$product = wc_get_product( $item['product_id'] );

								if ( $product ) {
									$title = $product->get_title();
								}
							}

							$options[] = [
								'value' => $reward_data['site_id'] . '|' . $reward_data['order_id'],
								'label' => $title,
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

	/**
	 * Process the Submission
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function handle_submission(): void {
		add_action(
			'template_redirect',
			function () {
				if ( empty( $_POST[ self::NONCE_ACTION . '-nonce' ] ) ) {
					Log::debug( 'Nonce empty' );
					return;
				}

				if ( ! wp_verify_nonce( self::NONCE_ACTION . '-nonce', self::NONCE_ACTION ) ) {
					Log::debug( 'Invalid nonce' );
					return;
				}

				$form_data = $this->get_form_data();

				Log::debug( 'Data', $form_data );

				if ( ! $this->passed_validation() ) {
					Log::debug( 'Validation failed' );
					return;
				}

				dd( $form_data );
				$user  = new Bidder();
				$title = sprintf(
					'%s %s %s',
					$form_data[ self::FIELD_TYPE ],
					__( 'from', 'goodbids' ),
					$user->get_username()
				);

				$support_post = [
					'post_type'   => goodbids()->support->get_post_type(),
					'author'      => 1,
					'post_title'  => $title,
					'post_status' => 'private',
					'meta_input'  => [
						self::FIELD_TYPE       => $form_data[ self::FIELD_TYPE ],
						self::FIELD_AUCTION_ID => $form_data[ self::FIELD_AUCTION_ID ],
						self::FIELD_BID_ID     => $form_data[ self::FIELD_BID_ID ],
						self::FIELD_REWARD_ID  => $form_data[ self::FIELD_REWARD_ID ],
						'request_nature'       => $form_data['_request_nature'],
						'request_description'  => $form_data['_request_description'],
					],
				];

				if ( ! goodbids()->support->create_request( $support_post ) ) {
					return;
				}

				wp_safe_redirect( add_query_arg( 'success', 1, ) );
				exit;
			}
		);
	}
}
