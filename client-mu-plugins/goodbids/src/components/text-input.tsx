import { forwardRef } from 'react';
import clsx from 'clsx';
import { P } from './typography';

export type TextInputProps = React.InputHTMLAttributes<HTMLInputElement> & {
	endAdornment?: React.ReactElement;
	error?: string;
	label: string;
	startAdornment?: React.ReactElement;
	supportingText?: string;
};

export const TextInput = forwardRef<HTMLInputElement, TextInputProps>(
	function TextInput(props, ref) {
		const {
			disabled,
			endAdornment,
			error,
			id,
			label,
			startAdornment,
			supportingText,
			...rest
		} = props;

		const inputClasses = clsx(
			'w-full rounded-md border border-solid bg-transparent py-2 !text-gb-lg outline-none transition-colors hover:ring-2 focus:ring-2',
			{
				'hover:ring-gb-gray-100-300 border-gb-gray-300 focus:border-gb-green-700 focus:ring-gb-green-700':
					!error,
				'border-gb-red-500 hover:ring-gb-red-500 focus:border-gb-red-500 focus:ring-gb-red-500':
					error,
				'pr-8': endAdornment,
				'pr-3': !endAdornment,
				'pl-8': startAdornment,
				'pl-3': !startAdornment,
			},
		);

		const labelClasses = clsx('text-gb-md font-bold text-gb-green-700', {
			'text-gb-red-500': error,
		});

		return (
			<div className="flex flex-col gap-2">
				<label htmlFor={id} className={labelClasses}>
					{label}
				</label>
				<div className="relative">
					{startAdornment && (
						<div className="absolute left-2 top-1/2 flex -translate-y-1/2 items-center justify-center text-gb-green-900">
							{startAdornment}
						</div>
					)}
					<input
						className={inputClasses}
						disabled={disabled}
						id={id}
						ref={ref}
						{...rest}
					/>
					{endAdornment && (
						<div className="absolute right-2 top-1/2 flex -translate-y-1/2 items-center justify-center text-gb-green-900">
							{endAdornment}
						</div>
					)}
				</div>

				{error && (
					<span className="text-gb-md text-gb-red-500">{error}</span>
				)}

				{supportingText && <P>{supportingText}</P>}
			</div>
		);
	},
);
