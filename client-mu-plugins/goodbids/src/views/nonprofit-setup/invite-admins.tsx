import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../components/button-link';
import { Card } from './components/card';
import { CardHeading } from './components/card-heading';

export function InviteAdmins() {
	return (
		<Card>
			<CardHeading
				title={__('Invite Administrators', 'goodbids')}
				content={__(
					'Grant site access to additional users so they can help edit the site and build auctions. Users can be created with a Site Admin or Junior Site Admin role. New users will receive an account activation email, and will need to update their temporary password and enable two-factor authentication to access your site. ',
					'goodbids',
				)}
			>
				<div className="w-full max-w-60">
					<ButtonLink
						target="_blank"
						variant="solid"
						href={gbNonprofitSetup.addUsersURL}
					>
						{__('Add New Users', 'goodbids')}
					</ButtonLink>
				</div>
			</CardHeading>
		</Card>
	);
}
