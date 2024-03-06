type ErrorWrapperProps = {
	children: React.ReactNode;
};

export function ErrorWrapper({ children }: ErrorWrapperProps) {
	return (
		<div className="w-full rounded-md bg-error-bg p-4 text-error-text">
			{children}
		</div>
	);
}
