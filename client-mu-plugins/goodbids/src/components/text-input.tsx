import { forwardRef } from 'react';
import clsx from 'clsx';
import { Tooltip } from './tooltip';

export type TextInputProps = React.InputHTMLAttributes<HTMLInputElement> & {
	label: string;
	error?: string;
	tooltip: string;
	startIcon?: React.ReactElement;
};

export const TextInput = forwardRef<HTMLInputElement, TextInputProps>(
	function TextInput(props, ref) {
		const { disabled, error, label, id, tooltip, startIcon, ...rest } =
			props;

		const inputClasses = clsx('rounded-md w-full border bg-white', {
			'border-gray-300': !error,
			'border-error-bg': error,
			'pl-6 p-2': startIcon,
			'p-2': !startIcon,
		});

		const labelClasses = clsx('text-admin-label font-bold', {
			'text-error-bg': error,
		});

		return (
			<div className="flex flex-col gap-2">
				<div className="flex gap-3 items-center">
					<label htmlFor={id} className={labelClasses}>
						{label}
					</label>
					<Tooltip>{tooltip}</Tooltip>
				</div>
				<div className="relative">
					<input
						className={inputClasses}
						disabled={disabled}
						id={id}
						ref={ref}
						{...rest}
					/>
					{startIcon && (
						<div className="absolute left-2 top-1/2 -translate-y-1/2">
							{startIcon}
						</div>
					)}
				</div>

				{error && <span className="text-error-bg">{error}</span>}
			</div>
		);
	},
);
