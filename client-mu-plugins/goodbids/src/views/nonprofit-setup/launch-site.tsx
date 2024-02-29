import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../components/button-link';
import { Card } from './components/card';
import { getBaseAdminUrl } from '../../utils/get-base-url';
import { CardHeading } from './components/card-heading';

// TODO: Replace with the actual URL or action
const LAUNCH_SITE_URL = '/#';

export function LaunchSite() {
	const baseUrl = getBaseAdminUrl();

	return (
		<Card>
			<CardHeading
				title={__('Launch Site', 'goodbids')}
				content={__(
					'Launch your GOODBIDS site to the world!',
					'goodbids',
				)}
			>
				<div className="w-full max-w-40">
					<ButtonLink
						variant="solid"
						href={`${baseUrl}${LAUNCH_SITE_URL}`}
					>
						{__('Go Live', 'goodbids')}
					</ButtonLink>
				</div>
			</CardHeading>
		</Card>
	);
}
