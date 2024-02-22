export type ProgressBarProps = {
	progress: number;
};

export function ProgressBar({ progress }: ProgressBarProps) {
	return (
		<div className="w-full bg-admin-main/5 rounded-md">
			<div
				className="h-3 bg-admin-main rounded-md transition-all duration-1000 ease-in-out"
				style={{ width: `${progress}%` }}
			/>
		</div>
	);
}
