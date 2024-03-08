import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';
import { Footnote } from '../components/footnote';

export function UploadLogo() {
	return (
		<MultiStepHeading
			title={__('Upload Site Logo', 'goodbids')}
			content={
				<>
					{__(
						'Create a JPG or PNG logo file with square dimensions no less than 48px48px. Click the button below, then click on the Header to edit the template. Click the logo area, then use the upload icon to add your image. Click Save to update the template.',
						'goodbids',
					)}
				</>
			}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.uploadLogoURL}
				>
					{__('Add Logo to Site Header', 'goodbids')}
				</ButtonLink>
			</div>

			<Footnote>
				{__('This button takes you to the')}{' '}
				<i>{__('Design > Patterns > Template Parts > Header', 'goodbids')}</i> {__('section of the Apperance Editor.')}
			</Footnote>
			
		</MultiStepHeading>
	);
}
