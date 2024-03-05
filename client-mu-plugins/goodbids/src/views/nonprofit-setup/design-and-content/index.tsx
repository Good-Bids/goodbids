import { __ } from '@wordpress/i18n';
import { Card } from '../components/card';
import { MultiStep } from '../components/multi-step';
import { CardHeading } from '../components/card-heading';
import { SiteAppearance } from './site-appearance';
import { UploadLogo } from './upload-logo';
import { CustomizeHomepage } from './customize-homepage';
import { Advanced } from './advanced';
import { ConfirmSiteAccessibility } from './confirm-site-accessibility';

export function DesignAndContent() {
	return (
		<Card>
			<CardHeading title={__('Design and Content', 'goodbids')} />

			<MultiStep
				defaultStep="appearance"
				steps={{
					appearance: {
						label: __('Site Appearance', 'goodbids'),
						component: <SiteAppearance />,
					},
					'upload-logo': {
						label: __('Upload Logo', 'goodbids'),
						component: <UploadLogo />,
					},
					'customize-homepage': {
						label: __('Customize Homepage', 'goodbids'),
						component: <CustomizeHomepage />,
					},
					'confirm-site-accessibility': {
						label: __('Confirm Site Accessibility', 'goodbids'),
						component: <ConfirmSiteAccessibility />,
					},
					advanced: {
						label: __('Advanced', 'goodbids'),
						component: <Advanced />,
						fade: true,
					},
				}}
			/>
		</Card>
	);
}
