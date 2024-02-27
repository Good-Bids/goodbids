type ErrorWrapperProps = {
	children: React.ReactNode;
};

export function ErrorWrapper({ children }: ErrorWrapperProps) {
	return (
		<div className="w-full bg-error-bg text-error-text p-4 rounded-md">
			{children}
		</div>
	);
}
