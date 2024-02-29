import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { getBaseAdminUrl } from '../../../utils/get-base-url';
import { MultiStepHeading } from '../components/multi-step-heading';

const WOOCOMMERCE_PAYMENTS_URL =
	'/wp-admin/admin.php?page=wc-settings&tab=checkout';

export function SetUpPayments() {
	const baseUrl = getBaseAdminUrl();

	return (
		<MultiStepHeading
			title={__('Set Up Payments', 'goodbids')}
			content={__(
				'Set up payments for your GOODBIDS site. Click the button below to review and update your site settings. You can revisit these post-launch on the WooCommerce > Settings > Payments page in the WordPress Admin.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={`${baseUrl}${WOOCOMMERCE_PAYMENTS_URL}`}
				>
					{__('Set Up Payments', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
