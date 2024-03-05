import { __ } from '@wordpress/i18n';
import { Card } from '../components/card';
import { FinalizeDetails } from './finalize-details';
import { SetUpPayments } from './set-up-payments';
import { MultiStep } from '../components/multi-step';
import { ConfigureShipping } from './configure-shipping';
import { ActivateExtensions } from './activate-extensions';
import { CreateWooCommerceStore } from './create-woocommerce-store';
import { CardHeading } from '../components/card-heading';

export function CompleteSetUp() {
	return (
		<Card>
			<CardHeading
				title={__('Complete Setup', 'goodbids')}
				content={__(
					"Before you can launch your site on the GOODBIDS network, you'll need to complete the steps below.",
					'goodbids',
				)}
			/>

			<MultiStep
				defaultStep="finalize-details"
				steps={{
					'finalize-details': {
						label: __('Finalize Details', 'goodbids'),
						component: <FinalizeDetails />,
					},
					'create-woocommerce-store': {
						label: __('Create WooCommerce Store', 'goodbids'),
						component: <CreateWooCommerceStore />,
					},
					'set-up-payments': {
						label: __('Set Up Payments', 'goodbids'),
						component: <SetUpPayments />,
					},
					'update-shipping': {
						label: __('Configure Shipping', 'goodbids'),
						component: <ConfigureShipping />,
					},
					'activate-extensions': {
						label: __('Activate Extensions', 'goodbids'),
						component: <ActivateExtensions />,
					},
				}}
			/>
		</Card>
	);
}
