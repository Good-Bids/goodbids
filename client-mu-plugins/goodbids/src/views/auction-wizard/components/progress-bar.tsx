export type ProgressBarProps = {
	progress: number;
};

export function ProgressBar({ progress }: ProgressBarProps) {
	return (
		<div className="w-full overflow-hidden rounded-t-sm bg-gb-green-100">
			<div
				className="h-4 bg-gb-green-700 transition-all duration-1000 ease-in-out"
				style={{ width: `${progress}%` }}
			/>
		</div>
	);
}
