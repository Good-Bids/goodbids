import * as Primitive from '@radix-ui/react-radio-group';
import React from 'react';

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
				className="bg-white w-6 h-6 rounded-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-1 focus:ring-admin-accent focus:ring-offset-2 border-none"
			>
				<Primitive.Indicator className="flex items-center justify-center w-full h-full relative after:content-[''] after:block after:w-3 after:h-3 after:rounded-full after:bg-admin-main" />
			</Primitive.Item>
			<label htmlFor={id} className="text-admin-content">
				{children}
			</label>
		</div>
	);
}

type RadioInputProps = Primitive.RadioGroupProps & {
	label: string;
};

export function RadioInput(props: RadioInputProps) {
	const { label, ...rest } = props;

	return (
		<div className="flex flex-col gap-2">
			<span className="text-admin-large text-admin-main font-bold">
				{label}
			</span>
			<Primitive.Root {...rest} className="flex flex-col gap-2" />
		</div>
	);
}
