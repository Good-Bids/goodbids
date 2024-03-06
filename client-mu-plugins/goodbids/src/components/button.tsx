import clsx from 'clsx';

type ButtonProps = React.ButtonHTMLAttributes<HTMLButtonElement> & {
	variant?: 'solid' | 'outline' | 'warning';
};

export function Button({ variant = 'outline', ...rest }: ButtonProps) {
	const classes = clsx('cursor-pointer px-6 py-2 text-admin-content', {
		'focus:outline-opacity-50 w-full max-w-80 rounded-admin-sm border-none bg-admin-main text-white transition-colors hover:bg-admin-accent hover:text-black focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 disabled:cursor-not-allowed disabled:bg-gray-500 disabled:text-white':
			variant === 'solid',
		'rounded-admin-sm border border-solid border-admin-main text-admin-main':
			variant === 'outline',
		'rounded-admin-sm border border-solid border-error-bg text-error-bg':
			variant === 'warning',
	});

	return <button {...rest} className={classes} />;
}
