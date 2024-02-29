import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { getBaseAdminUrl } from '../../../utils/get-base-url';
import { MultiStepHeading } from '../components/multi-step-heading';

const WOOCOMMERCE_SHIPPING_URL =
	'/wp-admin/admin.php?page=wc-settings&tab=shipping';

export function UpdateShipping() {
	const baseUrl = getBaseAdminUrl();

	return (
		<MultiStepHeading
			title={__('Update Shipping', 'goodbids')}
			content={__(
				'Click the button below to update your shipping settings. You can revisit these post-launch on the WooCommerce > Settings > Shipping page in the WordPress Admin.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={`${baseUrl}${WOOCOMMERCE_SHIPPING_URL}`}
				>
					{__('Update Shipping Settings', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
