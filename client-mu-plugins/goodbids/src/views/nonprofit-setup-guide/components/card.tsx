type CardProps = {
	children: React.ReactNode;
};

export function Card({ children }: CardProps) {
	return (
		<section className="flex w-full flex-col border-2 border-solid border-gb-green-700">
			{children}
		</section>
	);
}
