import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';

export function ManagePayments() {
	return (
		<MultiStepHeading
			title={__('Manage Payments', 'goodbids')}
			content={__(
				'You can manage your Stripe account connection with GOODBIDS using the button below. If you skipped payment setup during onboarding, you will need to create or connect a Stripe account before launching your site.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.connectStripeURL}
				>
					{__('Manage Stripe', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
