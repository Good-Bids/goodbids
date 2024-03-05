import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';

export function UploadSiteLogo() {
	return (
		<MultiStepHeading
			title={__('Upload Logo', 'goodbids')}
			content={__(
				'Upload a logo with square dimensions no less than 48x48px by navigating to Design > Patterns > Template Parts > Header in the Appearance Editor. Once there, click on the Header to start editing the template. Then click the Logo area to access the component. Select the "Add a Site Logo" upload button to the left of the site title to open the Media Library. Once you have successfully added your logo, click Save to update the template.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.updateLogoURL}
				>
					{__('Modify Site Header', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
