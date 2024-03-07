import { forwardRef } from 'react';
import clsx from 'clsx';
import { P } from './typography';

export type TextAreaProps =
	React.TextareaHTMLAttributes<HTMLTextAreaElement> & {
		error?: string;
		label: string;
		startAdornment?: React.ReactElement;
		supportingText?: string;
	};

export const TextArea = forwardRef<HTMLTextAreaElement, TextAreaProps>(
	function TextArea(props, ref) {
		const {
			disabled,
			error,
			label,
			id,
			startAdornment,
			supportingText,
			...rest
		} = props;

		const inputClasses = clsx(
			'w-full rounded-md border border-solid bg-transparent py-2 !text-gb-lg outline-none transition-colors hover:ring-2 focus:ring-2',
			{
				'border-gb-gray-300 hover:ring-gb-gray-300 focus:border-gb-green-700 focus:ring-gb-green-700':
					!error,
				'border-gb-red-500 hover:ring-gb-red-500 focus:border-gb-red-500 focus:ring-gb-red-500':
					error,
				'pl-6 pr-3': startAdornment,
				'px-3': !startAdornment,
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
					<textarea
						className={inputClasses}
						disabled={disabled}
						id={id}
						ref={ref}
						{...rest}
					/>
					{startAdornment && (
						<div className="absolute left-2 top-1/2 -translate-y-1/2">
							{startAdornment}
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
