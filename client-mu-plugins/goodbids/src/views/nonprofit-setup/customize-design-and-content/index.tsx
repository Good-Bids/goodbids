import { __ } from '@wordpress/i18n';
import { Card } from '../components/card';
import { MultiStep } from '../components/multi-step';

import { CardHeading } from '../components/card-heading';
import { UpdateStyle } from './update-style';
import { UploadSiteLogo } from './upload-site-logo';
import { ReviewHomepage } from './review-homepage';
import { Advanced } from './advanced';

export function CustomizeDesignAndContent() {
	return (
		<Card>
			<CardHeading
				title={__('Customize Design and Content', 'goodbids')}
				content={__(
					"Before you can launch your GOODBIDS site, you'll need to complete the steps below.",
					'goodbids',
				)}
			/>

			<MultiStep
				defaultStep="update-style"
				steps={{
					'update-style': {
						label: __('1 Update Style', 'goodbids'),
						component: <UpdateStyle />,
					},
					'upload-site-logo': {
						label: __('2 Upload Site Logo', 'goodbids'),
						component: <UploadSiteLogo />,
					},
					'review-homepage': {
						label: __('3 Review Homepage', 'goodbids'),
						component: <ReviewHomepage />,
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
