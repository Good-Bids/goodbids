import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { Create } from './create';
import { EditFlow } from './edit';
import { TooltipProvider } from '~/components/tooltip';
import { useState } from 'react';

function Wizard() {
	const rewardId = new URLSearchParams(document.location.search).get(
		gbAuctionWizard.editRewardParam,
	);

	if (rewardId) {
		return <EditFlow rewardId={parseInt(rewardId, 10)} />;
	}

	return <Create />;
}

export function Driver() {
	const [queryClient] = useState(() => new QueryClient());

	return (
		<QueryClientProvider client={queryClient}>
			<TooltipProvider>
				<Wizard />
			</TooltipProvider>
		</QueryClientProvider>
	);
}
