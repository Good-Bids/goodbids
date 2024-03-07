type ErrorWrapperProps = {
	children: React.ReactNode;
};

export function ErrorWrapper({ children }: ErrorWrapperProps) {
	return (
		<div className="box-border w-full rounded-md bg-gb-red-500 p-4 text-gb-gray-100">
			{children}
		</div>
	);
}
