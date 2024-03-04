import { Logo } from '../../components/logo';

type WrapperProps = {
	children: React.ReactNode;
};

export function Wrapper({ children }: WrapperProps) {
	return (
		<div className="pr-5 py-3 flex flex-col gap-8">
			<Logo />

			<div className="w-full flex flex-wrap gap-8">{children}</div>
		</div>
	);
}
