type CardProps = {
	children: React.ReactNode;
};

export function Card({ children }: CardProps) {
	return (
		<section className="border-2 border-admin-main border-solid w-full flex flex-col">
			{children}
		</section>
	);
}
