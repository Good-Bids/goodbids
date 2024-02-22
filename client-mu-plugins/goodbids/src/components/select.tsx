import * as Primitive from '@radix-ui/react-select';
import { Tooltip } from './tooltip';
import clsx from 'clsx';

type SelectProps = Primitive.SelectProps & {
	label: string;
	tooltip: string;
	error?: string;
	id: string;
};

export function Select(props: SelectProps) {
	const { children, label, tooltip, error, id, ...rest } = props;

	const triggerClasses = clsx(
		'inline-flex items-center justify-between rounded-md text-admin-content bg-white shadow-inner hover:bg-gray-300 outline-none border h-9 w-full',
		{
			'border-error-bg': error,
			'border-gray-300': !error,
		},
	);

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

			<Primitive.Root {...rest}>
				<Primitive.Trigger className={triggerClasses}>
					<Primitive.Value />
					<Primitive.Icon />
				</Primitive.Trigger>

				<Primitive.Portal>
					<Primitive.Content className="overflow-hidden bg-gray-100 rounded-md shadow-sm">
						<Primitive.Viewport>{children}</Primitive.Viewport>
					</Primitive.Content>
				</Primitive.Portal>
			</Primitive.Root>

			{error && <span className="text-error-bg">{error}</span>}
		</div>
	);
}

type SelectItemProps = Primitive.SelectItemProps;

export function SelectItem(props: SelectItemProps) {
	const { children, ...rest } = props;

	return (
		<Primitive.Item
			{...rest}
			className="text-admin-content first:rounded-t-md last:rounded-b-md even:bg-gray-100 hover:bg-gray-300 data flex items-center p-1"
		>
			<Primitive.ItemText>{children}</Primitive.ItemText>
		</Primitive.Item>
	);
}
