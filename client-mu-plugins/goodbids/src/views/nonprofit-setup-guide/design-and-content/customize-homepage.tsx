import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';

export function CustomizeHomepage() {
	return (
		<MultiStepHeading
			title={__('Customize Homepage content', 'goodbids')}
			content={__(
				'Your homepage includes some placeholder content to get started! Customize your homepage by adding a Title, Description, Video, and additional information about your Nonprofit. Use the Block Editor by hitting the "+" icon within the page area to add additional content blocks as needed.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.customizeHomepageURL}
				>
					{__('Modify Front Page ', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
