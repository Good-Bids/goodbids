import { __ } from '@wordpress/i18n';
import { getBaseAdminUrl } from '../../../utils/get-base-url';
import { MultiStepExpansion } from '../components/multi-step-expansion';
import { MultiStepHeading } from '../components/multi-step-heading';
import { Link } from '../../../components/link';

const JETPACK_URL = '/wp-admin/admin.php?page=jetpack#/dashboard';
const WOO_SHIPPING_AND_TAX_URL = '#';

export function ActivateExtensions() {
	const baseUrl = getBaseAdminUrl();

	return (
		<>
			<MultiStepHeading
				title={__('Activate Extensions', 'goodbids')}
				content={__(
					'We commending activating these plugins to get the most out of your GOODBIDS Nonprofit site.',
					'goodbids',
				)}
			/>

			<MultiStepExpansion
				items={[
					{
						title: __('Jetpack', 'goodbids'),
						content: __(
							'Jetpack is a plugin that provides security, performance, and site management tools.',
							'goodbids',
						),
						component: (
							<Link href={`${baseUrl}${JETPACK_URL}`}>
								Connect Jetpack
							</Link>
						),
					},
					{
						title: __('Woo Shipping and Tax', 'goodbids'),
						content: __(
							'Woo Shipping and Tax is a plugin that provides shipping and tax calculation tools.',
							'goodbids',
						),
						component: (
							<Link
								href={`${baseUrl}${WOO_SHIPPING_AND_TAX_URL}`}
							>
								Install Woo Shipping and Tax
							</Link>
						),
					},
				]}
			/>
		</>
	);
}
