import { __ } from '@wordpress/i18n';
import { Card } from '../components/card';
import { ConfirmSiteAccessibility } from './confirm-accessibility';
import { PostLaunchTools } from './post-launch-tools';
import { MultiStep } from '../components/multi-step';
import { PreviewSite } from './preview-site';
import { CardHeading } from '../components/card-heading';

export function PrepareForLaunch() {
	return (
		<Card>
			<CardHeading
				title={__('Prepare for Launch', 'goodbids')}
				content={__(
					'You’re almost there! Before you launch your site, please review the steps below to ensure your site is ready to go live. ',
					'goodbids',
				)}
			/>

			<MultiStep
				defaultStep="finalize-details"
				steps={{
					'finalize-details': {
						label: __('Confirm Site Accessibility', 'goodbids'),
						component: <ConfirmSiteAccessibility />,
					},
					'create-woocommerce-store': {
						label: __('Preview Site', 'goodbids'),
						component: <PreviewSite />,
					},
					'set-up-payments': {
						label: __('Review Post-Launch Tools', 'goodbids'),
						component: <PostLaunchTools />,
					},
				}}
			/>
		</Card>
	);
}
