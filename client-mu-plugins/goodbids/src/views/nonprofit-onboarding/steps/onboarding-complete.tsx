import AuctionStartImage from '../../../../assets/images/auction-start.png';
import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { H1, P } from '../../../components/typography';

export function OnboardingCompleteStep() {
	return (
		<div className="w-full flex flex-col items-center py-10 gap-4">
			<img src={AuctionStartImage} alt=""/>

			<H1>{__('Onboarding Complete', 'goodbids')}</H1>

			<div className="max-w-xl pb-8">
				<P>
					{__(
						'Hooray! We’ve confirmed that you set up payments. You’ve now completed the required steps for onboarding and you’re ready to start customizing your site. Click the button below to turn on the lights and see our site setup dashboard, which will walk you through all the next steps to build out and launch your site. ',
						'goodbids',
					)}
				</P>
			</div>

			<div className="flex gap-2 flex-wrap">
				<ButtonLink href={gbNonprofitOnboarding.onboardingCompleteUrl} target="_blank">
					{__('Go to my site', 'goodbids')}
				</ButtonLink>

				<ButtonLink href={gbNonprofitOnboarding.adminUrl}>
					{__('Go to my Dashboard', 'goodbids')}
				</ButtonLink>
			</div>

			<ButtonLink href={gbNonprofitOnboarding.setupGuideUrl}>
				{__('Explore Setup Guide', 'goodbids')}
			</ButtonLink>
		</div>
	);
}
