import { H3, P } from '~/components/typography';

type MultiStepHeadingProps = {
	title: string;
	content: React.ReactNode;
	children?: React.ReactNode;
};

export function MultiStepHeading({
	title,
	content,
	children,
}: MultiStepHeadingProps) {
	return (
		<div className="flex h-full w-full flex-col items-center justify-between gap-3">
			<div className="flex w-full flex-col gap-3 text-left">
				<H3 as="h2">{title}</H3>
				<P>{content}</P>
			</div>

			{children}
		</div>
	);
}
