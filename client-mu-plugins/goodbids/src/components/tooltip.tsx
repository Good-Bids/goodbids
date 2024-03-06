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
				<button className="flex h-5 w-5 items-center justify-center rounded-full border-none bg-gray-300 hover:bg-gray-500 focus:ring-2 focus:ring-admin-main focus:ring-opacity-50">
					<InfoIcon width={12} />
				</button>
			</Primitive.Trigger>
			<Primitive.Portal>
				<Primitive.Content
					side="right"
					className="z-[1000] max-w-60 rounded-md bg-gray-300 p-2 text-admin-content shadow-md"
				>
					{children}
					<Primitive.Arrow className="fill-gray-300" />
				</Primitive.Content>
			</Primitive.Portal>
		</Primitive.Root>
	);
}
