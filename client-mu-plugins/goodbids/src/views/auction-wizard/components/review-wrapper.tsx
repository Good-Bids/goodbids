type ReviewWrapperProps = {
	children: React.ReactNode;
};

export function ReviewWrapper({ children }: ReviewWrapperProps) {
	return (
		<div className="flex w-full max-w-80 flex-col gap-4 rounded-md bg-gray-300 p-3 transition-all">
			{children}
		</div>
	);
}
