import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';

export function ConfirmSiteAccessibility() {
	return (
		<MultiStepHeading
			title={__('Confirm Site Accessibility', 'goodbids')}
			content={__(
				'All GOODBIDS Nonprofit sites are required to meet accessibility guidelines. Once your site is live, the Accessibility Checker Pro plugin will be able to scan your pages for issues. After launch, review the site-wide accessibility report and resolve any issues flagged on your content and auction pages.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.accessibilityCheckerURL}
				>
					{__('Run Accessibility Checker', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
