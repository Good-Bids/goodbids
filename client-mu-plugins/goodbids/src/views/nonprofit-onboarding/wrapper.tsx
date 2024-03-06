import { Logo } from '../../components/logo';
import { ProgressBar, ProgressBarProps } from '../../components/progress-bar';

type WrapperProps = ProgressBarProps & {
	children: React.ReactNode;
};

export function Wrapper({ children, progress }: WrapperProps) {
	return (
		<div className="flex flex-col gap-8 py-3 pr-5">
			<ProgressBar progress={progress} />
			<div className="flex items-center justify-between">
				<Logo />
			</div>

			{children}
		</div>
	);
}
