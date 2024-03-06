type TipsProps = {
	children: React.ReactNode;
};

export function Tips({ children }: TipsProps) {
	return (
		<section className="flex max-w-80 flex-col gap-4 rounded-md bg-admin-main/25 p-3 text-black">
			<h2 className="m-0 text-3xl font-bold text-black">Tips</h2>
			{children}
		</section>
	);
}
