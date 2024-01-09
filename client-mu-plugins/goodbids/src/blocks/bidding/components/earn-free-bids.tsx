import { DEMO_DATA } from '../utils/demo-data';
import { initialState } from '../utils/get-initial-state';
import { WaveIcon } from './wave-icon';

function FreeBidsContent() {
	const { startTime, nextBid, freeBids } = initialState;

	if (new Date(startTime) > new Date()) {
		if (DEMO_DATA.userId) {
			return (
				<p className="m-0">
					Place one of the first five <b>paid bids</b> in this auction
					or{' '}
					<a
						className="font-bold underline"
						href={initialState.shareUrl}
					>
						share GOODBIDS with a friend
					</a>{' '}
					to <b>earn a free bid</b>!
				</p>
			);
		}
	}

	if (DEMO_DATA.userId) {
		if (freeBids) {
			return (
				<p className="m-0">
					GOODBID <b>${nextBid}</b> now or{' '}
					<a
						className="font-bold underline"
						href={initialState.shareUrl}
					>
						share GOODBIDS with a friend
					</a>{' '}
					to <b>earn a free bid</b>!
				</p>
			);
		}

		return (
			<p className="m-0">
				<a className="font-bold underline" href={initialState.shareUrl}>
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
			<a className="font-bold underline" href={initialState.shareUrl}>
				share GOODBIDS with a friend
			</a>{' '}
			to <b>earn a free bid</b>!
		</p>
	);
}

export function EarnFreeBids() {
	const { endTime } = initialState;

	if (new Date(endTime) < new Date()) {
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
