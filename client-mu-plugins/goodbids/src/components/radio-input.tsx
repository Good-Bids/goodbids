import * as Primitive from '@radix-ui/react-radio-group';
import React from 'react';
import { Tooltip } from './tooltip';

type RadioItemProps = Primitive.RadioGroupItemProps & {
	children: React.ReactNode;
};

export function RadioItem(props: RadioItemProps) {
	const { children, id, ...rest } = props;

	return (
		<div className="flex items-center gap-2">
			<Primitive.Item
				id={id}
				{...rest}
				className="h-6 w-6 cursor-pointer rounded-full border-none bg-white shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-1 focus:ring-admin-accent focus:ring-offset-2"
			>
				<Primitive.Indicator className="relative flex h-full w-full items-center justify-center after:block after:h-3 after:w-3 after:rounded-full after:bg-admin-main after:content-['']" />
			</Primitive.Item>
			<label htmlFor={id} className="text-admin-content">
				{children}
			</label>
		</div>
	);
}

type RadioInputProps = Primitive.RadioGroupProps & {
	label: string;
	tooltip?: string;
};

export function RadioInput(props: RadioInputProps) {
	const { label, tooltip, ...rest } = props;

	return (
		<div className="flex flex-col gap-2">
			<div className="flex gap-2">
				<span className="text-admin-large font-bold text-admin-main">
					{label}
				</span>
				{tooltip && <Tooltip>{tooltip}</Tooltip>}
			</div>
			<Primitive.Root {...rest} className="flex flex-col gap-2" />
		</div>
	);
}
