import clsx from 'clsx';

type ButtonProps = React.ButtonHTMLAttributes<HTMLButtonElement> & {
	variant?: 'solid' | 'outline' | 'ghost';
};

export function Button({ variant = 'outline', ...rest }: ButtonProps) {
	const classes = clsx(
		'w-full cursor-pointer rounded-full px-6 py-3 text-gb-md outline-none transition-all focus:ring-2 focus:ring-offset-2 active:animate-pulse disabled:cursor-not-allowed',
		{
			'border-none bg-gb-green-700 text-white hover:bg-gb-green-100 hover:text-gb-green-900 focus:ring-gb-green-700 hover:focus:text-gb-green-900 disabled:bg-gb-gray-500 disabled:text-white':
				variant === 'solid',
			'border-2 border-solid border-gb-green-700 bg-transparent text-gb-green-700 hover:bg-gb-green-100 hover:text-gb-green-900 focus:ring-gb-green-700 disabled:bg-gb-gray-500':
				variant === 'outline',
			'border-none bg-transparent text-gb-green-700 hover:bg-gb-green-100 hover:underline focus:underline focus:ring-gb-green-700 disabled:text-gb-gray-500':
				variant === 'ghost',
		},
	);

	return <button {...rest} className={classes} />;
}
