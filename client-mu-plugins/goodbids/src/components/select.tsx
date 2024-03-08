import * as Primitive from '@radix-ui/react-select';
import clsx from 'clsx';
import { CaretDownIcon } from './icons/caret-down-icon';
import { P } from './typography';

type SelectProps = Primitive.SelectProps & {
	error?: string;
	id: string;
	label: string;
	supportingText?: string;
};

export function Select(props: SelectProps) {
	const { children, label, error, id, supportingText, ...rest } = props;

	const triggerClasses = clsx(
		'inline-flex h-9 w-full items-center justify-between rounded-md border bg-transparent text-gb-lg outline-none transition-colors hover:ring-2 focus:ring-2',
		{
			'border-gb-gray-300 hover:ring-gb-gray-300 focus:border-gb-green-700 focus:ring-gb-green-700':
				!error,
			'border-gb-red-500 hover:ring-gb-red-500 focus:border-gb-red-500 focus:ring-gb-red-500':
				error,
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

			<Primitive.Root {...rest}>
				<Primitive.Trigger id={id} className={triggerClasses}>
					<Primitive.Value />
					<Primitive.Icon asChild>
						<CaretDownIcon />
					</Primitive.Icon>
				</Primitive.Trigger>

				<Primitive.Portal>
					<Primitive.Content className="overflow-hidden rounded-md border border-solid border-gb-gray-300 bg-gb-gray-100 shadow-sm">
						<Primitive.Viewport>{children}</Primitive.Viewport>
					</Primitive.Content>
				</Primitive.Portal>
			</Primitive.Root>

			{error && <span className="text-error-bg">{error}</span>}

			{supportingText && <P>{supportingText}</P>}
		</div>
	);
}

type SelectItemProps = Primitive.SelectItemProps;

export function SelectItem(props: SelectItemProps) {
	const { children, ...rest } = props;

	return (
		<Primitive.Item
			{...rest}
			className="flex items-center p-1 text-gb-lg text-gb-green-900 transition-colors first:rounded-t-md last:rounded-b-md even:bg-gb-gray-200 hover:bg-green-100"
		>
			<Primitive.ItemText>{children}</Primitive.ItemText>
		</Primitive.Item>
	);
}
