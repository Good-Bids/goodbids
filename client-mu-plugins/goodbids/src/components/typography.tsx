type TypographyProps = {
	as?: 'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6' | 'p' | 'span' | 'div';
	children: React.ReactNode;
};

export const H1 = ({ as = 'h1', children }: TypographyProps) => {
	const Component = as;

	return (
		<Component className="m-0 !p-0 !text-gb-2xl !font-bold text-gb-green-700">
			{children}
		</Component>
	);
};

export const H2 = ({ as = 'h2', children }: TypographyProps) => {
	const Component = as;

	return (
		<Component className="m-0 !text-gb-xl !font-bold text-gb-green-700">
			{children}
		</Component>
	);
};

export const H3 = ({ as = 'h3', children }: TypographyProps) => {
	const Component = as;

	return (
		<Component className="m-0 !text-gb-lg !font-bold">{children}</Component>
	);
};

export const P = ({ as = 'p', children }: TypographyProps) => {
	const Component = as;

	return <Component className="m-0 text-gb-md">{children}</Component>;
};
