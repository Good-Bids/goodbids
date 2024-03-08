import { __ } from '@wordpress/i18n';
import { ButtonLink } from '~/components/button-link';
import { H1, P } from '~/components/typography';
import { CoinManImage } from '~/components/images/coin-man';

export function OnboardingCompleteStep() {
	return (
		<div className="flex flex-col items-center gap-8 p-10">
			<CoinManImage className="aspect-auto h-60 py-10" />

			<div className="flex flex-col gap-3">
				<H1>{__('Onboarding complete', 'goodbids')}</H1>

				<P>
					{__(
						'Hooray! We’ve confirmed that you set up payments. You’ve now completed the required steps for onboarding and you’re ready to start customizing your site. Click the button below to turn on the lights and see our site setup dashboard, which will walk you through all the next steps to build out and launch your site. ',
						'goodbids',
					)}
				</P>
			</div>

			<div className="flex w-full flex-col gap-3">
				<ButtonLink
					variant="solid"
					href={gbNonprofitOnboarding.setupGuideUrl}
				>
					{__('Continue to site guide', 'goodbids')}
				</ButtonLink>
			</div>
		</div>
	);
}
