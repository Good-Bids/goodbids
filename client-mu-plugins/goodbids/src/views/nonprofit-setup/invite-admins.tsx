import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../components/button-link';
import { Card } from './components/card';
import { getBaseAdminUrl } from '../../utils/get-base-url';
import { CardHeading } from './components/card-heading';

const ADD_USER_URL = '/wp-admin/user-new.php';

export function InviteAdmins() {
	const baseUrl = getBaseAdminUrl();

	return (
		<Card>
			<CardHeading
				title={__('Invite Administrators', 'goodbids')}
				content={__(
					"Invite other users to be administrators of your GOODBIDS account. Administrators can create and manage auctions, view reports, and more. You can invite as many administrators as you'd like.",
					'goodbids',
				)}
			>
				<div className="w-full max-w-40">
					<ButtonLink
						variant="solid"
						href={`${baseUrl}${ADD_USER_URL}`}
					>
						{__('Add Users', 'goodbids')}
					</ButtonLink>
				</div>
			</CardHeading>
		</Card>
	);
}
