import React from 'react';

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

type MetricsProps = {
	blocks: MetricBlockProps[];
};

export function Metrics({ blocks }: MetricsProps) {
	return (
		<div className="grid grid-cols-3 gap-5 my-4">
			{blocks.map((block) => (
				<MetricBlock key={block.type} {...block} />
			))}
		</div>
	);
}
