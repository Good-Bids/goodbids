import { H2, P } from '../../../components/typography';

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
		<div className="flex flex-col justify-between w-full h-full items-center gap-3">
			<div className="w-full text-left flex flex-col gap-3">
				<H2>{title}</H2>
				<P>{content}</P>
			</div>

			{children}
		</div>
	);
}
