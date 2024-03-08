export function EditorMetrics() {
	return (
		<div className="-mt-[0.75rem] grid grid-cols-3 gap-2">
			<EditorMetricBlock type="Raised" value="$0" />
			<EditorMetricBlock type="Last Bid" value="--" />
			<EditorMetricBlock type="Bids" value={0} />
		</div>
	);
}

type EditorMetricsBlockProps = {
	type: string;
	value: string | number;
};

export function EditorMetricBlock({ type, value }: EditorMetricsBlockProps) {
	return (
		<div className="rounded-sm bg-contrast-5 px-4 py-2">
			<span className="block text-sm font-bold">{type}</span>
			<span className="block text-sm">{value}</span>
		</div>
	);
}
