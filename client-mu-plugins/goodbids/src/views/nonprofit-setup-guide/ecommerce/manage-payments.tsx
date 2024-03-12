import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';

export function ManagePayments() {
	return (
		<MultiStepHeading
			title={__('Manage Payments', 'goodbids')}
			content={__(
				'If you skipped payment set up during onboarding, you can create or connect a Stripe account now using the button below.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.connectStripeURL}
				>
					{__('Connect Stripe', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
