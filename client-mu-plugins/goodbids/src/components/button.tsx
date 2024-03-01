import clsx from 'clsx';

type ButtonProps = React.ButtonHTMLAttributes<HTMLButtonElement> & {
	variant?: 'solid' | 'outline' | 'warning';
};

export function Button({ variant = 'outline', ...rest }: ButtonProps) {
	const classes = clsx('py-2 px-6 cursor-pointer text-admin-content', {
		'border-none rounded-admin-sm bg-admin-main text-white hover:bg-admin-accent hover:text-black transition-colors focus:outline-opacity-50 focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 w-full max-w-80 disabled:cursor-not-allowed disabled:bg-gray-500 disabled:text-white':
			variant === 'solid',
		'border border-solid rounded-admin-sm border-admin-main text-admin-main':
			variant === 'outline',
		'border border-solid rounded-admin-sm border-error-bg text-error-bg':
			variant === 'warning',
	});

	return <button {...rest} className={classes} />;
}
