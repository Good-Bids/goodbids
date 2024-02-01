export function getIsLastBidder(userId?: number, lastBidder?: number | null) {
	if (userId === undefined || lastBidder === undefined) {
		return false;
	}

	return userId === lastBidder;
}
