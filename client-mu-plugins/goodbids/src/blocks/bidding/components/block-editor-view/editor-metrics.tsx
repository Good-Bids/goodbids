export function EditorMetrics() {
	return (
		<div className="grid grid-cols-3 gap-2 -mt-[0.75rem]">
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
		<div className="px-4 py-2 rounded-sm bg-contrast-5">
			<span className="block text-sm font-bold">{type}</span>
			<span className="block text-sm">{value}</span>
		</div>
	);
}
