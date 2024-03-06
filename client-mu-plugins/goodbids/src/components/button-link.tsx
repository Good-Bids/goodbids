import clsx from 'clsx';

type ButtonProps = React.AnchorHTMLAttributes<HTMLAnchorElement> & {
	variant?: 'solid' | 'outline' | 'warning';
};

export function ButtonLink({ variant = 'outline', ...rest }: ButtonProps) {
	const classes = clsx(
		'block cursor-pointer px-6 py-2 text-center text-admin-content no-underline',
		{
			'focus:outline-opacity-50 rounded-admin-sm border-none bg-admin-main text-white transition-colors hover:bg-admin-accent hover:text-black focus:text-white focus:ring-2 focus:ring-admin-main focus:ring-opacity-50':
				variant === 'solid',
			'rounded-admin-sm border border-solid border-admin-main text-admin-main':
				variant === 'outline',
			'rounded-admin-sm border border-solid border-error-bg text-error-bg':
				variant === 'warning',
		},
	);

	return <a {...rest} className={classes} />;
}
