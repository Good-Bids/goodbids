<?php
/**
 * Screen Options
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Admin;

use WP_Screen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Screen Options class.
 *
 * @since 1.0.0
 */
class ScreenOptions {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const NONCE = 'goodbids_screen_options_nonce';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $id;

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $admin_page = null;

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $options;

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $option_data = [];

	/**
	 * @since 1.0.0
	 * @var ?string $legend.
	 */
	private ?string $legend = null;

	/**
	 * The class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id
	 * @param array  $options
	 *
	 * @return void
	 */
	public function __construct( string $id, array $options = [] ) {
		$this->id = $id;

		$this->set_options( $options );

		add_filter( 'screen_settings', [ $this, 'show_screen_options' ], 10, 2 );
	}

	/**
	 * Initializes the Screen Options.
	 *
	 * @since 1.0.0
	 *
	 * @param string $admin_page
	 *
	 * @return void
	 */
	public function init( string $admin_page ): void {
		$this->admin_page = $admin_page;

		$this->register_screen_options();
		$this->update_options();
	}

	/**
	 * Options Meta Key
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_meta_key(): string {
		return $this->id;
	}

	/**
	 * Set the screen options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options The options array
	 */
	public function set_options( array $options ): void {
		$this->options = $options;
	}

	/**
	 * Get the options.
	 *
	 * @since 1.0.0
	 *
	 * @return array The options.
	 */
	private function get_options(): array {
		return $this->options;
	}

	/**
	 * Register the screen options.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_screen_options(): void {
		$screen = get_current_screen();

		if ( ! is_object( $screen ) || $this->admin_page !== $screen->id ) {
			return;
		}

		foreach ( $this->get_options() as $key => $option ) {
			$value = ! empty( $option['default'] ) ? $option['default'] : '';

			add_screen_option(
				"{$this->get_meta_key()}-$key",
				[
					'option' => $key,
					'value'  => $value,
				]
			);
		}
	}

	/**
	 * Set the legend for the screen options.
	 *
	 * @since 1.0.0
	 *
	 * @param string $legend
	 *
	 * @return void
	 */
	public function set_legend( string $legend ): void {
		$this->legend = $legend;
	}

	/**
	 * Get the Legend
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_legend(): string {
		if ( ! is_null( $this->legend ) ) {
			return $this->legend;
		}

		return __( 'Display Options', 'goodbids' );
	}

	/**
	 * Get the option data.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_option_data(): array {
		if ( $this->option_data ) {
			return $this->option_data;
		}

		$data = get_user_meta( get_current_user_id(), $this->get_meta_key(), true );

		if ( ! $data ) {
			return [];
		}

		$this->option_data = $data;

		return $this->option_data;
	}

	/**
	 * Get an option value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get_option( string $key ): mixed {
		$data    = $this->get_option_data();
		$options = $this->get_options();

		if ( ! array_key_exists( $key, $data ) ) {
			if ( ! empty( $options[ $key ]['default'] ) ) {
				return $options[ $key ]['default'];
			}

			return null;
		}

		return $data[ $key ];
	}

	/**
	 * The HTML markup to wrap around each option.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function before(): void {
		?>
		<fieldset>
			<?php wp_nonce_field( self::NONCE, self::NONCE ); ?>

			<legend><?php echo esc_html( $this->get_legend() ); ?></legend>

			<div class="metabox-prefs">
				<div class="<?php echo esc_attr( $this->get_meta_key() ); ?>">
		<?php
	}

	/**
	 * The HTML markup to close the options.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function after(): void {
		$button = get_submit_button( __( 'Apply', 'goodbids' ), 'button', 'screen-options-apply', false );
				?>
				</div>
			</div>
		</fieldset>
		<br class="clear">
		<?php
		echo $button; // phpcs:ignore
	}

	/**
	 * Display a screen option.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $key    The option key
	 * @param  array  $option The option array
	 *
	 * @return void;
	 */
	public function show_option( string $key, array $option ): void {
		$id     = "{$this->get_meta_key()}-$key";
		$data   = $this->get_option_data();

		if ( ! empty( $option['label'] ) ) {
			?>
			<label for="<?php echo esc_attr( $id ); ?>">
				<?php echo esc_html( $option['label'] ); ?>
			</label>
			<?php
		}

		goodbids()->admin->render_field( $key, $option, $this->get_meta_key(), $data, false );
	}

	/**
	 * Render the screen options block.
	 *
	 * @since 1.0.0
	 *
	 * @param string $screen_settings The screen options settings.
	 * @param WP_Screen $screen       An object of screen options data.
	 *
	 * @return string The filtered screen options block.
	 */
	public function show_screen_options( string $screen_settings, WP_Screen $screen ): string {
		if ( $this->admin_page !== $screen->id ) {
			return $screen_settings;
		}

		ob_start();

		$this->before();

		foreach ( $this->get_options() as $key => $option ) {
			$this->show_option( $key, $option );
		}

		$this->after();

		return $screen_settings . ob_get_clean();
	}

	/**
	 * Save the screen option setting.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function update_options(): void {
		if ( empty( $_POST[ self::NONCE ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE ] ) ), self::NONCE ) ) {
			return;
		}

		if ( empty( $_POST[ $this->get_meta_key() ] ) || ! is_array( $_POST[ $this->get_meta_key() ] ) ) {
			return;
		}

		$values = wp_unslash( $_POST[ $this->get_meta_key() ] ); // phpcs:ignore
		$data   = [];

		foreach ( $values as $key => $val ) {
			$data[ $key ] = sanitize_text_field( $val );
		}

		update_user_meta( get_current_user_id(), $this->get_meta_key(), $data );

		$this->option_data = $data;
	}
}
