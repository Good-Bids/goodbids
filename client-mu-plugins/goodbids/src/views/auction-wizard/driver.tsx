import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { Create } from './create';
import { Edit } from './edit';
import { useState } from 'react';
import { z } from 'zod';
import { Clone } from './clone';

const modeSchema = z.enum(gbAuctionWizard.modeParamOptions).catch('create');

function Wizard() {
	const mode = modeSchema.parse(
		new URLSearchParams(document.location.search).get(
			gbAuctionWizard.modeParam,
		),
	);

	const auctionId = new URLSearchParams(document.location.search).get(
		gbAuctionWizard.auctionIdParam,
	);

	const rewardId = new URLSearchParams(document.location.search).get(
		gbAuctionWizard.rewardIdParam,
	);

	if (mode === 'edit' && auctionId && rewardId) {
		return (
			<Edit
				auctionId={parseInt(auctionId, 10)}
				rewardId={parseInt(rewardId, 10)}
			/>
		);
	}

	if (mode === 'clone' && auctionId && rewardId) {
		return (
			<Clone
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
