type CardProps = {
	children: React.ReactNode;
};

export function Card({ children }: CardProps) {
	return (
		<section className="border-gb-gray-100-200 w-full max-w-584 rounded-sm border border-solid bg-white">
			{children}
		</section>
	);
}
