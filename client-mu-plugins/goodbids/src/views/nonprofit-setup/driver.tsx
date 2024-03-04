import { __ } from '@wordpress/i18n';
import { H1 } from '../../components/typography';
import { CompleteSetUp } from './complete-set-up';
import { CustomizeDesignAndContent } from './customize-design-and-content';
import { InviteAdmins } from './invite-admins';
import { LaunchSite } from './launch-site';
import { PlanAnAuction } from './plan-an-auction';
import { PrepareForLaunch } from './prepare-for-launch';
import { Wrapper } from './wrapper';
import { SiteStatus } from './site-status';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { useState } from 'react';

export function Driver() {
	const [queryClient] = useState(() => new QueryClient());

	return (
		<QueryClientProvider client={queryClient}>
			<Wrapper>
				<div className="max-w-2xl flex flex-col items-start gap-4">
					<H1>{__('Site Setup', 'goodbids')}</H1>
					<CompleteSetUp />
					<CustomizeDesignAndContent />
					<PlanAnAuction />
					<InviteAdmins />
					<PrepareForLaunch />
					<LaunchSite />
				</div>
				<SiteStatus />
			</Wrapper>
		</QueryClientProvider>
	);
}
