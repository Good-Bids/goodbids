import clsx from 'clsx';

type ButtonProps = React.AnchorHTMLAttributes<HTMLAnchorElement> & {
	variant?: 'solid' | 'outline' | 'ghost';
};

export function ButtonLink({ variant = 'outline', ...rest }: ButtonProps) {
	const classes = clsx(
		'box-border block w-full cursor-pointer rounded-full px-6 py-3 text-center text-gb-md no-underline outline-none transition-all focus:ring-2 focus:ring-offset-2 active:animate-pulse disabled:cursor-not-allowed',
		{
			'border-none bg-gb-green-700 text-white hover:bg-gb-green-100 hover:text-gb-green-900 focus:text-white focus:ring-gb-green-700 disabled:text-white':
				variant === 'solid',
			'border-2 border-solid border-gb-green-700 bg-transparent text-gb-green-700 hover:bg-gb-green-100 hover:text-gb-green-900 focus:text-gb-green-700 focus:ring-gb-green-700':
				variant === 'outline',
			'border-none bg-transparent text-gb-green-700 hover:bg-gb-green-100 hover:underline focus:text-gb-green-700 focus:underline focus:ring-gb-green-700':
				variant === 'ghost',
		},
	);

	return <a {...rest} className={classes} />;
}
