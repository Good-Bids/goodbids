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
				className="h-6 w-6 cursor-pointer rounded-full border-none bg-gb-gray-100 outline-none hover:bg-gb-gray-300 focus:ring-2 focus:ring-gb-green-700 focus:ring-offset-2 data-[state='checked']:bg-gb-green-100"
			>
				<Primitive.Indicator className="relative flex h-full w-full items-center justify-center after:block after:h-3 after:w-3 after:rounded-full after:bg-gb-green-700 after:content-['']" />
			</Primitive.Item>
			<label htmlFor={id} className="text-gb-lg">
				{children}
			</label>
		</div>
	);
}

export function RadioInput(props: Primitive.RadioGroupProps) {
	return <Primitive.Root {...props} className="flex flex-col gap-2" />;
}
