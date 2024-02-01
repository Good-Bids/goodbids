import { useBiddingState } from '../store';
import { WaveIcon } from './wave-icon';

function FreeBidsContent() {
	const { auctionStatus, currentBid, freeBidsAvailable, userId } =
		useBiddingState();

	if (
		auctionStatus === 'upcoming' ||
		auctionStatus === 'starting' ||
		auctionStatus === 'prelive'
	) {
		if (userId) {
			return (
				<p className="m-0">
					Place one of the first five <b>paid bids</b> in this auction
					or{' '}
					<span className="font-bold underline">
						share GOODBIDS with a friend
					</span>{' '}
					to <b>earn a free bid</b>!
				</p>
			);
		}
	}

	if (userId) {
		if (freeBidsAvailable) {
			return (
				<p className="m-0">
					GOODBID <b>${currentBid}</b> now or{' '}
					<span className="font-bold underline">
						share GOODBIDS with a friend
					</span>{' '}
					to <b>earn a free bid</b>!
				</p>
			);
		}

		return (
			<p className="m-0">
				<span className="font-bold underline">
					Share GOODBIDS with a friend
				</span>{' '}
				to <b>earn a free bid</b>!
			</p>
		);
	}

	return (
		<p className="m-0">
			GOODBIDS users earn <b>free bids</b> when they place one of the{' '}
			<b>first five paid bids</b> in an auction or{' '}
			<span className="font-bold underline">
				share GOODBIDS with a friend
			</span>{' '}
			to <b>earn a free bid</b>!
		</p>
	);
}

export function EarnFreeBids() {
	const { auctionStatus } = useBiddingState();

	if (auctionStatus === 'closed' || auctionStatus === 'closing') {
		return null;
	}

	return (
		<div className="flex items-start gap-4 bg-contrast-5 rounded p-4">
			<div className="h-6 w-6">
				<WaveIcon />
			</div>
			<div className="flex flex-col gap-3">
				<p className="m-0">
					<b>Earn free bids:</b>
				</p>
				<FreeBidsContent />
			</div>
		</div>
	);
}
