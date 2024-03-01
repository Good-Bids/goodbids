import clsx from 'clsx';

type ButtonProps = React.AnchorHTMLAttributes<HTMLAnchorElement> & {
	variant?: 'solid' | 'outline' | 'warning';
};

export function ButtonLink({ variant = 'outline', ...rest }: ButtonProps) {
	const classes = clsx(
		'py-2 px-6 cursor-pointer text-admin-content no-underline text-center block',
		{
			'border-none rounded-admin-sm bg-admin-main text-white hover:bg-admin-accent hover:text-black transition-colors focus:outline-opacity-50 focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 focus:text-white':
				variant === 'solid',
			'border border-solid rounded-admin-sm border-admin-main text-admin-main':
				variant === 'outline',
			'border border-solid rounded-admin-sm border-error-bg text-error-bg':
				variant === 'warning',
		},
	);

	return <a {...rest} className={classes} />;
}
