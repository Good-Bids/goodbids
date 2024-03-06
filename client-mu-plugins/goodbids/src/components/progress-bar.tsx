export type ProgressBarProps = {
	progress: number;
};

export function ProgressBar({ progress }: ProgressBarProps) {
	return (
		<div className="w-full rounded-md bg-admin-main/5">
			<div
				className="h-3 rounded-md bg-admin-main transition-all duration-1000 ease-in-out"
				style={{ width: `${progress}%` }}
			/>
		</div>
	);
}
