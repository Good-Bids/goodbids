import { __ } from '@wordpress/i18n';
import { H1 } from '../../components/typography';
import { CompleteSetUp } from './complete-set-up';
import { CustomizeDesignAndContent } from './customize-design-and-content';
import { InviteAdmins } from './invite-admins';
import { LaunchSite } from './launch-site';
import { PlanAnAuction } from './plan-an-auction';
import { PrepareForLaunch } from './prepare-for-launch';

export function Driver() {
	return (
		<main className="flex flex-col gap-4 p-2">
			<H1>{__('Site Setup', 'goodbids')}</H1>
			<div className="w-full max-w-4xl flex flex-col items-center gap-4">
				<CompleteSetUp />
				<CustomizeDesignAndContent />
				<PlanAnAuction />
				<InviteAdmins />
				<PrepareForLaunch />
				<LaunchSite />
			</div>
		</main>
	);
}
