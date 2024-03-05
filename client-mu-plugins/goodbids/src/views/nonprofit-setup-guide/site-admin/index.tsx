import { __ } from '@wordpress/i18n';
import { Card } from '../components/card';
import { MultiStep } from '../components/multi-step';
import { CardHeading } from '../components/card-heading';
import { GeneralSettings } from './general-settings';
import { ManageUsers } from './manage-users';
import { ModerateComments } from './moderate-comments';
import { Invoices } from './invoices';

export function SiteAdmin() {
	return (
		<Card>
			<CardHeading title={__('Site Admin', 'goodbids')} />

			<MultiStep
				defaultStep="general-settings"
				steps={{
					'general-settings': {
						label: __('General Settings', 'goodbids'),
						component: <GeneralSettings />,
					},
					'manage-users': {
						label: __('Manage Users', 'goodbids'),
						component: <ManageUsers />,
					},
					'moderate-comments': {
						label: __('Moderate Comments', 'goodbids'),
						component: <ModerateComments />,
					},
					invoices: {
						label: __('Invoices', 'goodbids'),
						component: <Invoices />,
					},
				}}
			/>
		</Card>
	);
}
