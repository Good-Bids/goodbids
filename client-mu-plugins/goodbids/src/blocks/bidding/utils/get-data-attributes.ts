import { z } from 'zod';

const attributesSchema = z.object({
	auctionId: z.number(),
});

function getDataAttributes() {
	const root = document.getElementById('goodbids-bidding');
	const auctionId = root?.getAttribute('data-auction-id');

	return attributesSchema.parse({
		auctionId: auctionId ? parseInt(auctionId) : 0,
	});
}

export const attributes = getDataAttributes();
