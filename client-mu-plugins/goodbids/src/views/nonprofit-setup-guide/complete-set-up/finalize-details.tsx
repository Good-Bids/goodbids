import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';

export function FinalizeDetails() {
	return (
		<MultiStepHeading
			title={__('Finalize Details', 'goodbids')}
			content={__(
				'Review and update your site name, tagline, and timezone. You can revisit these post-launch in Settings > General. Additional site settings were updated on your behalf to meet GOODBIDS site requirements and will not need to be reviewed.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.optionsGeneralURL}
				>
					{__('Update Site Settings', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
