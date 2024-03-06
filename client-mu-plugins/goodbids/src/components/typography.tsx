type TypographyProps = {
	as?: 'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6' | 'p' | 'span' | 'div';
	children: React.ReactNode;
};

export const H1 = ({ as = 'h1', children }: TypographyProps) => {
	const Component = as;

	return (
		<Component className="m-0 !p-0 !text-admin-extra-large !font-bold">
			{children}
		</Component>
	);
};

export const H2 = ({ as = 'h2', children }: TypographyProps) => {
	const Component = as;

	return (
		<Component className="m-0 !text-admin-large !font-bold">
			{children}
		</Component>
	);
};

export const H3 = ({ as = 'h3', children }: TypographyProps) => {
	const Component = as;

	return (
		<Component className="m-0 !text-admin-medium !font-bold">
			{children}
		</Component>
	);
};

export const P = ({ as = 'p', children }: TypographyProps) => {
	const Component = as;

	return <Component className="m-0 text-admin-content">{children}</Component>;
};
