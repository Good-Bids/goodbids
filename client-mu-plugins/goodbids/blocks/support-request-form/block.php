<?php
/**
 * Support Request Form Block
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Blocks;

use GoodBids\Frontend\Notices;
use GoodBids\Frontend\SupportRequest;
use GoodBids\Plugins\ACF\ACFBlock;

/**
 * Class for Support Request Form Block
 *
 * @since 1.0.0
 */
class SupportRequestForm extends ACFBlock {

	/**
	 * Render the form
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render(): void {
		$form_data = [];

		if ( goodbids()->support->submission_processed() ) {
			goodbids()->notices->display_notice( Notices::REQUEST_SUBMITTED );
		} else {
			$form_data = goodbids()->support->get_form_data();
		}

		$button_class = 'text-gb-green-700 bg-gb-green-100 border-0 hover:bg-gb-green-500 hover:text-white focus:outline-none font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 cursor-pointer';

		if ( is_admin() ) {
			$button_class .= ' pointer-events-none';
		}
		?>
		<form method="post" action="" data-form-spinner="true" class="bg-gb-green-700 rounded p-4 relative group opacity-100 group-[.htmx-request]:opacity-50 transition-opacity">
			<div class="absolute flex justify-center w-full -translate-y-full">
				<svg xmlns="http://www.w3.org/2000/svg" class="relative inset-0 htmx-indicator w-14 h-14 animate-spin" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3C16.9706 3 21 7.02944 21 12H19C19 8.13401 15.866 5 12 5V3Z"></path></svg>
			</div>

            <?php
			if ( ! is_admin() ) :
				wp_nonce_field( SupportRequest::FORM_NONCE_ACTION, SupportRequest::FORM_NONCE_ACTION . '_nonce' );
			endif;
			?>

			<?php $this->inner_blocks(); ?>

			<div id="gb-support-form-target">
				<?php
				$this->render_error();

				foreach ( goodbids()->support->get_fields() as $key => $field ) :
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
		if ( ! goodbids()->support->get_error() ) {
			return;
		}
		?>
		<div class="border-gb-green-100 rounded p-4 mb-4">
			<p class="text-gb-green-100">
				<?php echo esc_html( goodbids()->support->get_error() ); ?>
			</p>
		</div>
		<?php
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
