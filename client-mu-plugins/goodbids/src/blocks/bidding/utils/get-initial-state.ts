export function getInitialState() {
	const root = document.getElementById('bidding-block');

	const auctionId = root?.getAttribute('data-auction-id');
	const initialBids = root?.getAttribute('data-initial-bids');
	const initialRaised = root?.getAttribute('data-initial-raised');
	const initialLastBid = root?.getAttribute('data-initial-last-bid');
	const initialEndTime = root?.getAttribute('initial-end-time');
	const initialFreeBids = root?.getAttribute('initial-free-bids');
	const initialUserBids = root?.getAttribute('initial-user-bids');
	const initialLastBidder = root?.getAttribute('initial-last-bidder');

	return {
		auctionId,
		bids: initialBids ? parseInt(initialBids, 10) : 0,
		raised: initialRaised ? parseInt(initialRaised, 10) : 0,
		lastBid: initialLastBid ? parseInt(initialLastBid, 10) : 0,
		endTime: initialEndTime ? parseInt(initialEndTime, 10) : 0,
		freeBids: initialFreeBids ? parseInt(initialFreeBids, 10) : 0,
		userBids: initialUserBids ? parseInt(initialUserBids, 10) : 0,
		lastBidder: initialLastBidder ? initialLastBidder : '',
	};
}
