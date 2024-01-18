import { DEMO_DATA } from '../utils/demo-data';
import { WaveIcon } from './wave-icon';
import { useAuction } from '../utils/auction-store';

function FreeBidsContent() {
	const { auctionStatus, currentBid, freeBidsAvailable } = useAuction();

	if (auctionStatus === 'live') {
		if (DEMO_DATA.userId) {
			return (
				<p className="m-0">
					Place one of the first five <b>paid bids</b> in this auction
					or{' '}
					<a
						className="font-bold underline"
						href={DEMO_DATA.shareUrl}
					>
						share GOODBIDS with a friend
					</a>{' '}
					to <b>earn a free bid</b>!
				</p>
			);
		}
	}

	if (DEMO_DATA.userId) {
		if (freeBidsAvailable) {
			return (
				<p className="m-0">
					GOODBID <b>${currentBid}</b> now or{' '}
					<a
						className="font-bold underline"
						href={DEMO_DATA.shareUrl}
					>
						share GOODBIDS with a friend
					</a>{' '}
					to <b>earn a free bid</b>!
				</p>
			);
		}

		return (
			<p className="m-0">
				<a className="font-bold underline" href={DEMO_DATA.shareUrl}>
					Share GOODBIDS with a friend
				</a>{' '}
				to <b>earn a free bid</b>!
			</p>
		);
	}

	return (
		<p className="m-0">
			GOODBIDS users earn <b>free bids</b> when they place one of the{' '}
			<b>first five paid bids</b> in an auction or{' '}
			<a className="font-bold underline" href={DEMO_DATA.shareUrl}>
				share GOODBIDS with a friend
			</a>{' '}
			to <b>earn a free bid</b>!
		</p>
	);
}

export function EarnFreeBids() {
	const { endTime } = useAuction();

	if (endTime < new Date()) {
		return null;
	}

	return (
		<div className="flex items-start gap-4 bg-gray-100 rounded p-4">
			<WaveIcon />
			<div className="flex flex-col gap-3">
				<p className="m-0">
					<b>Earn free bids:</b>
				</p>
				<FreeBidsContent />
			</div>
		</div>
	);
}
