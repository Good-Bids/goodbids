import {
	ProgressBar,
	ProgressBarProps,
} from '../auction-wizard/components/progress-bar';

type WrapperProps = ProgressBarProps & {
	children: React.ReactNode;
};

export function Wrapper({ children, progress }: WrapperProps) {
	return (
		<>
			<ProgressBar progress={progress} />

			{children}
		</>
	);
}
