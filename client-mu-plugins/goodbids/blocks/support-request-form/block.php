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
use GoodBids\Network\Nonprofit;
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

		$button_class = 'btn-fill-secondary text-md';

		if ( is_admin() ) {
			$button_class .= ' pointer-events-none';
		}
		?>
		<form method="post" action="" class="px-8 pt-4 pb-12 rounded-sm bg-contrast relative group opacity-100 group-[.htmx-request]:opacity-50 transition-opacity" data-form-spinner>
			<?php if ( ! is_admin() ) : ?>
				<div class="absolute inset-x-0 flex justify-center w-full -translate-y-full -bottom-2.5 text-base-2">
					<svg xmlns="http://www.w3.org/2000/svg" class="relative inset-0 htmx-indicator w-14 h-14 animate-spin" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3C16.9706 3 21 7.02944 21 12H19C19 8.13401 15.866 5 12 5V3Z"></path></svg>
				</div>
			<?php endif; ?>

			<?php
			if ( ! is_admin() ) :
				wp_nonce_field( SupportRequest::FORM_NONCE_ACTION, SupportRequest::FORM_NONCE_ACTION . '_nonce' );
			endif;
			?>

			<?php $this->inner_blocks(); ?>

			<div id="gb-support-form-target" class="relative z-10">
				<?php
				$this->render_error();

				foreach ( goodbids()->support->get_fields() as $key => $field ) :
					goodbids()->forms->render_field( $key, $field, '', $form_data );
				endforeach;
				?>
				<button
					type="submit"
					class="<?php echo esc_attr( $button_class ); ?>"
				>
					<?php esc_attr_e( 'Submit Request', 'goodbids' ); ?>
				</button>
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
		<div class="p-4 mb-4 rounded bg-gb-red-700">
			<p class="text-gb-red-100">
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
		$nonprofit = new Nonprofit( get_current_blog_id() );
		$template  = [
			[
				'core/heading',
				[
					'textColor' => 'base-2',
					'content'   => __( 'Request Support from', 'goodbids' ) . ' ' . $nonprofit->get_name(),
				],
			],
			[
				'core/paragraph',
				[
					'content'   => __( 'Use the form below to submit a support request to this Nonprofit. Your submission will be visible to administrators for this Nonprofit site as well as the GOODBIDS support team. We will respond as soon as we can.', 'goodbids' ),
					'textColor' => 'base-2',
				],
			],
		];

		printf(
			'<InnerBlocks template="%s" />',
			esc_attr( wp_json_encode( $template ) )
		);
	}
}
