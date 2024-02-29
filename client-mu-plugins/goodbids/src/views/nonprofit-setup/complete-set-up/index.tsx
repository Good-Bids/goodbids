import { __ } from '@wordpress/i18n';
import { Card } from '../components/card';
import { FinalizeDetails } from './finalize-details';
import { SetUpPayments } from './set-up-payments';
import { MultiStep } from '../components/multi-step';
import { UpdateShipping } from './update-shipping';
import { ActivateExtensions } from './activate-extensions';
import { Advanced } from './advanced';
import { CardHeading } from '../components/card-heading';

export function CompleteSetUp() {
	return (
		<Card>
			<CardHeading
				title={__('Complete Setup', 'goodbids')}
				content={__(
					"Before you can launch your GOODBIDS site, you'll need to complete the steps below.",
					'goodbids',
				)}
			/>

			<MultiStep
				defaultStep="finalize-details"
				steps={{
					'finalize-details': {
						label: __('1 Finalize Details', 'goodbids'),
						component: <FinalizeDetails />,
					},
					'set-up-payments': {
						label: __('2 Set Up Payments', 'goodbids'),
						component: <SetUpPayments />,
					},
					'update-shipping': {
						label: __('3 Update Shipping', 'goodbids'),
						component: <UpdateShipping />,
					},
					'activate-extensions': {
						label: __('4 Activate Extensions', 'goodbids'),
						component: <ActivateExtensions />,
					},
					advanced: {
						label: __('Advanced', 'goodbids'),
						component: <Advanced />,
						fade: true,
					},
				}}
			/>
		</Card>
	);
}
