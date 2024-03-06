import clsx from 'clsx';
import { H1, P } from '../../../components/typography';

type CardHeadingProps = {
	title: string;
	content?: string;
	children?: React.ReactNode;
};

export function CardHeading({ title, content, children }: CardHeadingProps) {
	const containerClasses = clsx('flex items-center justify-between gap-4', {
		'p-4': children,
	});

	const headingClasses = clsx('flex w-full flex-col gap-1', {
		'max-w-120': children,
		'p-4': !children,
	});

	return (
		<div className={containerClasses}>
			<div className={headingClasses}>
				<H1>{title}</H1>
				{content && <P>{content}</P>}
			</div>

			{children}
		</div>
	);
}
