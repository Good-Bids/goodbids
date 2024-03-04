import AuctionStartImage from '../../../../assets/images/auction-start.png';
import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { H1, P } from '../../../components/typography';

export function SetUpPaymentsStep() {
	return (
		<div className="w-full flex flex-col items-center py-10 gap-4">
			<img src={AuctionStartImage} />

			<H1>{__('Set Up Payments', 'goodbids')}</H1>

			<div className="max-w-xl pb-8">
				<P>
					{__(
						'Next, you’ll need to to create a Stripe account or connect an existing one to accept donations for auction bids. Click the button below, then click "Create or connect an account". If you prefer to connect your Stripe account through the "Enter account keys (advanced)" option, gather your Stripe API keys from',
						'goodbids',
					)}{' '}
					<a href="https://dashboard.stripe.com">
						{__('dashboard.stripe.com', 'goodbids')}
					</a>{' '}
					{__(
						'> Developers > API keys. Add your Publishable and Secret keys into the Live tab and click Save. Once your account is connected, we’ll be able to get you into your Nonprofit site.',
						'goodbids',
					)}
				</P>
			</div>

			<ButtonLink href={gbNonprofitOnboarding.setUpPaymentsUrl}>
				{__('Connect Stripe', 'goodbids')}
			</ButtonLink>
		</div>
	);
}
