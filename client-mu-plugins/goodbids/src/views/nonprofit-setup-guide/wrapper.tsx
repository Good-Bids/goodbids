import { Logo } from '../../components/logo';

type WrapperProps = {
	children: React.ReactNode;
};

export function Wrapper({ children }: WrapperProps) {
	return (
		<div className="flex flex-col gap-8 py-3 pr-5">
			<Logo />

			<div className="flex w-full flex-wrap gap-8">{children}</div>
		</div>
	);
}
