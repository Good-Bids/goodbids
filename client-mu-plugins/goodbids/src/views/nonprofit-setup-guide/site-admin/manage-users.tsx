import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepExpansion } from '../components/multi-step-expansion';

export function ManageUsers() {
	return (
		<MultiStepHeading
			title={__('Manage Users', 'goodbids')}
			content={__(
				'View Users associated with your Nonprofit and grant access to additional admins so they can help edit the site and build auctions.',
				'goodbids',
			)}
		>
			<MultiStepExpansion
				items={[
					{
						title: __('View Users', 'goodbids'),
						content: __(
							'See all admin and front-end users for your Nonprofit site. The links at the top of the All Users page allow you to filter the list by role (Administrator, Junior Site Admin, Customer).',
							'goodbids',
						),
						component: (
							<div className="w-full max-w-60 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={gbNonprofitSetupGuide.usersUrl}
								>
									{__('All Users', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
					{
						title: __('Invite a User', 'goodbids'),
						content: __(
							'Admins can be created with an Administrator or Junior Site Admin role. New users will receive an account activation email and will need to update their temporary password and enable two-factor authentication to access your site.',
							'goodbids',
						),
						component: (
							<div className="w-full max-w-60 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={gbNonprofitSetupGuide.addUsersURL}
								>
									{__('Add New User', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
				]}
			/>
		</MultiStepHeading>
	);
}
