export function getInitialState() {
	const gutenblock = document.getElementById('goodbids-bidding');
	console.log(gutenblock?.getAttribute('data-gutenberg-attributes'));

	const root = document.getElementById('bidding-block');

	const auctionId = root?.getAttribute('data-auction-id');
	const initialBids = root?.getAttribute('data-initial-bids');
	const initialRaised = root?.getAttribute('data-initial-raised');
	const initialLastBid = root?.getAttribute('data-initial-last-bid');
	const initialStartTime = root?.getAttribute('data-initial-start-time');
	const initialEndTime = root?.getAttribute('data-initial-end-time');
	const initialFreeBids = root?.getAttribute('data-initial-free-bids');
	const initialUserBids = root?.getAttribute('data-initial-user-bids');
	const initialLastBidder = root?.getAttribute('data-initial-last-bidder');
	const bidUrl = root?.getAttribute('data-bid-url');
	const prizeUrl = root?.getAttribute('data-prize-url');
	const nextBid = root?.getAttribute('data-next-bid');

	return {
		auctionId,
		bids: initialBids ? parseInt(initialBids, 10) : 0,
		raised: initialRaised ? parseInt(initialRaised, 10) : 0,
		lastBid: initialLastBid ? parseInt(initialLastBid, 10) : 0,
		startTime: initialStartTime || '',
		endTime: initialEndTime || '',
		freeBids: initialFreeBids ? parseInt(initialFreeBids, 10) : 0,
		userBids: initialUserBids ? parseInt(initialUserBids, 10) : 0,
		lastBidder: initialLastBidder || '',
		bidUrl: bidUrl || '',
		prizeUrl: prizeUrl || '',
		nextBid: nextBid ? parseInt(nextBid, 10) : 0,
	};
}

export const initialState = getInitialState();
