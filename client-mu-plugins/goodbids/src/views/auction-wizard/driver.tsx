import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { Create } from './create';
import { Edit } from './edit';
import { useState } from 'react';

function Wizard() {
	const auctionId = new URLSearchParams(document.location.search).get(
		gbAuctionWizard.auctionIdParam,
	);

	const rewardId = new URLSearchParams(document.location.search).get(
		gbAuctionWizard.editRewardParam,
	);

	if (auctionId && rewardId) {
		return (
			<Edit
				auctionId={parseInt(auctionId, 10)}
				rewardId={parseInt(rewardId, 10)}
			/>
		);
	}

	return <Create />;
}

export function Driver() {
	const [queryClient] = useState(() => new QueryClient());

	return (
		<QueryClientProvider client={queryClient}>
			<Wizard />
		</QueryClientProvider>
	);
}
