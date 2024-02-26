import { useBiddingState } from '../../store';
import { nonClosedStatuses } from '../../utils/statuses';
import { FreeBidsHeading } from './free-bids-heading';
import { FreeBidsInfo } from './free-bids-info';
import { FreeBidsContent } from './free-bids-content';

export function FreeBidsPromo() {
	const { auctionStatus } = useBiddingState();

	if (nonClosedStatuses.includes(auctionStatus)) {
		return (
			<div className="flex flex-col justify-start gap-4 p-4 rounded-sm bg-contrast-5">
				<FreeBidsHeading />
				<div className="flex flex-col gap-4 pl-6">
					<FreeBidsContent />
					<FreeBidsInfo />
				</div>
			</div>
		);
	}

	return null;
}
