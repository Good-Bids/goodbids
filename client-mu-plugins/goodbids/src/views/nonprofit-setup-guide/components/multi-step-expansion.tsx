import { H3, P } from '../../../components/typography';

type MultiStepExpansionItemType = {
	title: string;
	content: React.ReactNode;
	component: React.ReactNode;
};

type MultiStepExpansionProps = {
	items: MultiStepExpansionItemType[];
};

export function MultiStepExpansion({ items }: MultiStepExpansionProps) {
	return (
		<div className="w-full flex flex-col gap-3 pt-4">
			{items.map(({ title, content, component }) => (
				<div key={title} className="flex flex-col gap-2 pb-3 last:pb-0">
					<H3>{title}</H3>
					<P>{content}</P>
					<div className="w-full flex justify-center">
						{component}
					</div>
				</div>
			))}
		</div>
	);
}
