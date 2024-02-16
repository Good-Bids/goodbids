import { Logo } from './logo';
import { ProgressBar, ProgressBarProps } from './progress-bar';

type WrapperProps = ProgressBarProps & {
	children: React.ReactNode;
};

export function Wrapper({ children, progress }: WrapperProps) {
	return (
		<div className="pr-5 py-3 flex flex-col gap-8">
			<ProgressBar progress={progress} />
			<Logo />

			{children}
		</div>
	);
}
