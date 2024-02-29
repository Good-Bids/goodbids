import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';

export function PreviewSite() {
	return (
		<MultiStepHeading
			title={__('Preview Site', 'goodbids')}
			content={__(
				'Once all your content and auction pages are ready, click through your site to confirm everything looks good! As a reminder, only administrators for your site will be able to see front end pages until the site goes live.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetup.homeURL}
				>
					{__('Visit Site', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
