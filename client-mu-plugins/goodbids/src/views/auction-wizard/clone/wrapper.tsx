import { H2 } from '~/components/typography';
import { Navigation } from './navigation';

type WrapperProps = {
	children: React.ReactNode;
	title: string;
};

export function Wrapper({ children, title }: WrapperProps) {
	return (
		<>
			<div className="flex flex-col gap-8 px-10 pb-10 text-gb-green-900">
				<Navigation />

				<H2 as="h1">{title}</H2>

				<div>{children}</div>
			</div>
		</>
	);
}
