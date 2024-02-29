import { H3, P } from '../../../components/typography';

type MultiStepExpansionItemType = {
	title: string;
	content: string;
	component: React.ReactNode;
};

type MultiStepExpansionProps = {
	items: MultiStepExpansionItemType[];
};

export function MultiStepExpansion({ items }: MultiStepExpansionProps) {
	return (
		<div className="w-full flex flex-col gap-3 pt-4">
			{items.map(({ title, content, component }) => (
				<div
					key={title}
					className="flex flex-col gap-2 border-b-2 border-b-admin-main border-t-0 border-x-0 border-solid pb-3 last:border-b-0 last:pb-0"
				>
					<H3>{title}</H3>
					<P>{content}</P>
					{component}
				</div>
			))}
		</div>
	);
}
