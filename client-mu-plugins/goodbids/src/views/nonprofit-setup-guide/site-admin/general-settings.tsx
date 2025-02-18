import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';
import { Footnote } from '../components/footnote';

export function GeneralSettings() {
	return (
		<MultiStepHeading
			title={__('General Settings', 'goodbids')}
			content={__(
				'Review and update your site name, tagline, and timezone. Additional site settings were updated on your behalf to meet GOODBIDS requirements.',
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

			<Footnote>
				{__('This button takes you to the', 'goodbids')}{' '}
				<i>{__('Settings > General', 'goodbids')}</i>{' '}
				{__('page.', 'goodbids')}
			</Footnote>
		</MultiStepHeading>
	);
}
