import { z } from 'zod';

const attributesSchema = z.object({
	auctionId: z.number(),
});

function getDataAttributes() {
	const root = document.getElementById('goodbids-bidding');
	return attributesSchema.parse(
		JSON.parse(root?.getAttribute('data-block-attributes') || '{}'),
	);
}

export const attributes = getDataAttributes();
