function getDataAuction(auctionId: number) {
	return `data-auction-id=\\"${auctionId}\\"`;
}

function getAuctionId(auctionId: number) {
	return `\\"auctionId\\":${auctionId}`;
}

export function getContent(
	parentId: number,
	auctionId: number,
	content: string,
) {
	return content
		.replace(getAuctionId(parentId), getAuctionId(auctionId))
		.replace(getDataAuction(parentId), getDataAuction(auctionId));
}
