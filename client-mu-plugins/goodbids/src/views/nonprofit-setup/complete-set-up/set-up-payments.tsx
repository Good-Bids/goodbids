import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';

export function SetUpPayments() {
	return (
		<MultiStepHeading
			title={__('Set Up Payments', 'goodbids')}
			content={
				<>
					{__(
						'Create a Stripe account or connect an existing one to accept donations for auction bids. Click the button below, then click "Create or connect an account" on the WooCommerce > Settings > Payments > Stripe page. This will send you to',
						'goobids',
					)}{' '}
					<a href="https://connect.stripe.com">
						{__('connect.stripe.com', 'goodbids')}
					</a>
					.{' '}
					{__(
						'If you prefer to connect your Stripe account through the "Enter account keys (advanced)" option, gather your Stripe API keys from',
						'goodbids',
					)}{' '}
					<a href="https://dashboard.stripe.com">
						{__('dashboard.stripe.com', 'goodbids')}
					</a>{' '}
					{__(
						'> Developers > API keys. Add your Publishable and Secret keys into the Live tab and click Save.',
						'goodbids',
					)}
				</>
			}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetup.setUpPaymentURL}
				>
					{__('Connect Stripe', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
