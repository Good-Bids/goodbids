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
		<div className="rounded bg-contrast-5 px-4 py-2">
			<span className="font-bold text-sm block">{type}</span>
			<span className="text-sm block">{value}</span>
		</div>
	);
}
