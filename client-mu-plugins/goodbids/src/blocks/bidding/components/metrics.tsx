import { useAuction } from '../utils/auction-store';

type MetricType = 'bids' | 'raised' | 'last-bid' | 'winning-bid';

const metricTypes: Record<MetricType, string> = {
	bids: 'BIDS',
	raised: 'RAISED',
	'last-bid': 'LAST BID',
	'winning-bid': 'WINNING BID',
};

type MetricBlockProps = {
	type: MetricType;
	value: number;
};

function MetricBlock({ type, value }: MetricBlockProps) {
	return (
		<div className="flex flex-col text-center">
			<p className="m-0 font-thin uppercase has-x-small-font-size">
				{metricTypes[type]}
			</p>
			<p className="m-1 font-extrabold">
				{type === 'bids' ? value : `$${value.toLocaleString()}`}
			</p>
		</div>
	);
}

export function Metrics() {
	const { totalBids, lastBid, totalRaised, auctionStatus } = useAuction();

	return (
		<div className="grid grid-cols-3 gap-5 my-4">
			<MetricBlock type="bids" value={totalBids} />
			<MetricBlock type="raised" value={totalRaised} />
			<MetricBlock
				type={auctionStatus === 'closed' ? 'winning-bid' : 'last-bid'}
				value={lastBid}
			/>
		</div>
	);
}
