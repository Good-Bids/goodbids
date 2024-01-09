import { z } from 'zod';

const attributesSchema = z.object({
	auctionId: z.number(),
	socketUrl: z.string(),
	shareUrl: z.string(),
	rewardUrl: z.string(),
	bidUrl: z.string(),
	freeBidUrl: z.string(),
	createAccountUrl: z.string(),
	logInUrl: z.string(),
	startTime: z.string(), // TODO: Update to datetime once we can plug in start time from WP
});

function getDataAttributes() {
	const root = document.getElementById('goodbids-bidding');
	return attributesSchema.parse(
		JSON.parse(root?.getAttribute('data-gutenberg-attributes') || '{}'),
	);
}

export const attributes = getDataAttributes();
