import { Logo } from '../../components/images/logo';

type WrapperProps = {
	children: React.ReactNode;
};

export function Wrapper({ children }: WrapperProps) {
	return (
		<div className="flex flex-col items-start gap-8 py-3 pr-5">
			<Logo className="aspect-auto h-12" />

			<div className="flex w-full flex-wrap gap-8">{children}</div>
		</div>
	);
}
