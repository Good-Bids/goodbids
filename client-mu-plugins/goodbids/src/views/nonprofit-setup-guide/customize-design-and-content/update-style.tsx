import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';

export function UpdateStyle() {
	return (
		<MultiStepHeading
			title={__('Update Site Style', 'goodbids')}
			content={__(
				'Choose a Theme Style that best fits your Nonprofit’s look and feel, then click the pencil icon to make it your own. You can change the colors, typography, and sitewide layout. Your style preferences can be modified in the Appearance > Editor section.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.styleURL}
				>
					{__('Choose a Style', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
