import { __ } from '@wordpress/i18n';
import { H1 } from '../../components/typography';
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
