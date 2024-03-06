import AuctionStartImage from '../../../../assets/images/auction-start.png';
import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { H1, P } from '../../../components/typography';

export function ActivateAccessibilityCheckerStep() {
	return (
		<div className="w-full flex flex-col items-center py-10 gap-4">
			<img src={AuctionStartImage} alt="" />

			<H1>{__('Activate Accessibility Checker Pro', 'goodbids')}</H1>

			<div className="max-w-xl pb-8">
				<P>
					{__(
						"All GOODBIDS Nonprofit sites must meet web accessibility guidelines. The Accessibility Checker Pro plugin is required for your site and will help you review your site and become compliant with accessibility guidelines. Click the button below to activate the Accessibility Checker Pro plugin for your Nonprofit Site. If you have a personal license for Accessibility Checker Pro, you can enter it on the page to activate the plugin. If not, don't fret, just leave the license field blank and click the Activate License button, and we'll handle it from there!",
						'goodbids',
					)}
				</P>
			</div>

			<ButtonLink
				href={gbNonprofitOnboarding.accessibilityCheckerUrl}
			>
				{__('Activate Plugin', 'goodbids')}
			</ButtonLink>
		</div>
	);
}
