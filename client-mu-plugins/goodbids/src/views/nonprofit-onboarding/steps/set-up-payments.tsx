import { __ } from '@wordpress/i18n';
import { ButtonLink } from '~/components/button-link';
import { H1, P } from '~/components/typography';
import { CoinManImage } from '~/components/images/coin-man';

export function SetUpPaymentsStep() {
	return (
		<div className="flex flex-col items-center gap-8 p-10">
			<CoinManImage className="aspect-auto h-60 py-10" />

			<div className="flex flex-col gap-3">
				<H1>{__('Set up payments', 'goodbids')}</H1>

				<P>
					{__(
						'Next, you’ll need to to create a Stripe account or connect an existing one to accept donations for auction bids. Click the button below, then click "Create or connect an account"',
						'goodbids',
					)}
				</P>
			</div>

			<ButtonLink
				variant="solid"
				href={gbNonprofitOnboarding.setUpPaymentsUrl}
			>
				{__('Connect Stripe', 'goodbids')}
			</ButtonLink>
		</div>
	);
}
