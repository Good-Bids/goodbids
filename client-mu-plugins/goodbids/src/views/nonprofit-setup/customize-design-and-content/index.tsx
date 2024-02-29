import { __ } from '@wordpress/i18n';
import { Card } from '../components/card';
import { MultiStep } from '../components/multi-step';

import { CardHeading } from '../components/card-heading';
import { UpdateStyle } from './update-style';
import { UploadSiteLogo } from './upload-site-logo';
import { CustomizeHomepage } from './customize-homepage';
import { Advanced } from './advanced';

export function CustomizeDesignAndContent() {
	return (
		<Card>
			<CardHeading
				title={__('Customize Design and Content', 'goodbids')}
				content={__(
					"Card Description: Update your site's visual aesthetic and content to match your brand.",
					'goodbids',
				)}
			/>

			<MultiStep
				defaultStep="update-style"
				steps={{
					'update-style': {
						label: __('Update Site Style', 'goodbids'),
						component: <UpdateStyle />,
					},
					'upload-site-logo': {
						label: __('Upload Logo', 'goodbids'),
						component: <UploadSiteLogo />,
					},
					'review-homepage': {
						label: __('Customize Homepage Content', 'goodbids'),
						component: <CustomizeHomepage />,
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
