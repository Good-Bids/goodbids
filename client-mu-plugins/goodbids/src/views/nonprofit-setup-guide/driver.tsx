import { __ } from '@wordpress/i18n';
import { H1, P } from '../../components/typography';
import { PlanAndMonitor } from './plan-and-monitor';
import { Wrapper } from './wrapper';
import { SiteStatus } from './site-status';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { useState } from 'react';
import { SiteAdmin } from './site-admin';
import { Ecommerce } from './ecommerce';
import { DesignAndContent } from './design-and-content';

export function Driver() {
	const [queryClient] = useState(() => new QueryClient());

	return (
		<QueryClientProvider client={queryClient}>
			<Wrapper>
				<div className="flex max-w-2xl flex-col items-start gap-4">
					<H1>{__('Site Setup', 'goodbids')}</H1>
					<P>
						{__(
							'Please note: The Setup Guide is an online instructional manual. The box titled “Site Status” (on the top right) is the only section that will update based on your progress through the guide. The Site Status will update to reflect the current status of your Nonprofit Site, and will either read “Pending” or “Live,” along with information relevant to that status level.',
							'goodbids',
						)}
					</P>
					{(gbNonprofitSetupGuide.isBDPAdmin ||
						gbNonprofitSetupGuide.isAdmin) && (
						<>
							<SiteAdmin />
							<Ecommerce />
						</>
					)}
					<DesignAndContent />
					<PlanAndMonitor />
				</div>
				<SiteStatus />
			</Wrapper>
		</QueryClientProvider>
	);
}
