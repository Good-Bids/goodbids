import { __ } from '@wordpress/i18n';
import { Card } from './components/card';
import { CardHeading } from './components/card-heading';
import { Button } from '../../components/button';

export function LaunchSite() {
	return (
		<Card>
			<CardHeading
				title={__('Go Live on GOODBIDS', 'goodbids')}
				content={__(
					'You are ready to launch your site! Clicking the Launch Site button will update your site’s status to Live and allow non-Admin visitors to view your pages.',
					'goodbids',
				)}
			>
				<div className="w-full max-w-60">
					{/* TODO: Hook up action once available */}
					<Button variant="solid" disabled>
						{__('Launch Site', 'goodbids')}
					</Button>
				</div>
			</CardHeading>
		</Card>
	);
}
