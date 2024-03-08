import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { H1, P } from '../../../components/typography';
import { PuzzleManImage } from '~/components/images/puzzle-man';

export function ActivateAccessibilityCheckerStep() {
	return (
		<div className="flex flex-col items-center gap-8 p-10">
			<PuzzleManImage className="aspect-auto h-50 py-10" />

			<div className="flex flex-col gap-3">
				<H1>{__('Activate Accessibility Checker Pro', 'goodbids')}</H1>
				<P>
					{__(
						"All GOODBIDS Nonprofit sites must meet web accessibility guidelines. The Accessibility Checker Pro plugin is required for your site and will help you review your site and become compliant with accessibility guidelines. Click the button below to activate the Accessibility Checker Pro plugin for your Nonprofit Site. If you have a personal license for Accessibility Checker Pro, you can enter it on the page to activate the plugin. If not, don't fret, just leave the license field blank and click the Activate License button, and we'll handle it from there!",
						'goodbids',
					)}
				</P>
			</div>

			<ButtonLink
				variant="solid"
				href={gbNonprofitOnboarding.accessibilityCheckerUrl}
			>
				{__('Activate Plugin', 'goodbids')}
			</ButtonLink>
		</div>
	);
}
