export type MetricType = 'bids' | 'raised' | 'last-bid' | 'winning-bid';

const metricTypes: Record<MetricType, string> = {
	bids: 'Bids',
	raised: 'Raised',
	'last-bid': 'Last Bid',
	'winning-bid': 'Winning Bid',
};

export function formatMetricHeading(type: MetricType) {
	return metricTypes[type];
}

export function formatMetricValue(type: MetricType, value: number) {
	if (type === 'bids') {
		return `${Math.round(value)}`;
	}

	if (value < 1 && type !== 'raised') {
		return '--';
	}

	return `$${Math.round(value).toLocaleString()}`;
}
