type TipsProps = {
	children: React.ReactNode;
};

export function Tips({ children }: TipsProps) {
	return (
		<section className="flex flex-col gap-4 bg-admin-main/25 text-black max-w-80 p-3 rounded-md">
			<h2 className="text-3xl font-bold m-0 text-black">Tips</h2>
			{children}
		</section>
	);
}
