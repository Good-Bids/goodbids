import { Card } from '~/components/card';
import {
	ProgressBar,
	ProgressBarProps,
} from '../auction-wizard/components/progress-bar';

type WrapperProps = ProgressBarProps & {
	children: React.ReactNode;
};

export function Wrapper({ children, progress }: WrapperProps) {
	return (
		<div className="flex w-full justify-center pt-12">
			<Card>
				<ProgressBar progress={progress} />
				<div className="flex flex-col items-center gap-8 p-10">
					{children}
				</div>
			</Card>
		</div>
	);
}
