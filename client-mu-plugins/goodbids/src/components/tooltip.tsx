import * as Primitive from '@radix-ui/react-tooltip';
import { InfoIcon } from './info-icon';

type TooltipProps = {
	children: React.ReactNode;
};

export function TooltipProvider({ children }: TooltipProps) {
	return (
		<Primitive.TooltipProvider delayDuration={300} skipDelayDuration={100}>
			{children}
		</Primitive.TooltipProvider>
	);
}

export function Tooltip({ children }: TooltipProps) {
	return (
		<Primitive.Root>
			<Primitive.Trigger asChild>
				<button className="border-none hover:bg-gray-500 focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 rounded-full bg-gray-300 flex items-center justify-center h-5 w-5">
					<InfoIcon width={12} />
				</button>
			</Primitive.Trigger>
			<Primitive.Portal>
				<Primitive.Content
					side="right"
					className="text-admin-content max-w-60 bg-gray-300 shadow-md p-2 rounded-md z-[1000]"
				>
					{children}
					<Primitive.Arrow className="fill-gray-300" />
				</Primitive.Content>
			</Primitive.Portal>
		</Primitive.Root>
	);
}
