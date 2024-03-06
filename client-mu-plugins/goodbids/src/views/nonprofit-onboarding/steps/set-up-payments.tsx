import AuctionStartImage from '../../../../assets/images/auction-start.png';
import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { H1, P } from '../../../components/typography';

export function SetUpPaymentsStep() {
	return (
		<div className="w-full flex flex-col items-center py-10 gap-4">
			<img src={AuctionStartImage} alt="" />

			<H1>{__('Set Up Payments', 'goodbids')}</H1>

			<div className="max-w-xl pb-8">
				<P>
					{__(
						'Next, you’ll need to to create a Stripe account or connect an existing one to accept donations for auction bids. Click the button below, then click "Create or connect an account"',
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
