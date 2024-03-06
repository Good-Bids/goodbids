import { useAuctionWizardState } from '../store';
import { Button } from '~/components/button';
import { Logo } from '~/components/logo';
import { ProgressBar, ProgressBarProps } from '~/components/progress-bar';

type WrapperProps = ProgressBarProps & {
	children: React.ReactNode;
};

export function Wrapper({ children, progress }: WrapperProps) {
	const { step, setStep } = useAuctionWizardState();

	const handleBack = () => {
		if (step === 'finish') {
			return setStep('review');
		}

		if (step === 'review') {
			return setStep('auction');
		}

		if (step === 'auction') {
			return setStep('product');
		}

		if (step === 'product') {
			return setStep('start');
		}
	};

	return (
		<div className="flex flex-col gap-8 py-3 pr-5">
			<ProgressBar progress={progress} />
			<div className="flex items-center justify-between">
				<Logo />
				{step !== 'start' && step !== 'finish' && (
					<div className="flex items-center gap-4">
						<Button onClick={handleBack}>Back</Button>
						<Button
							variant="warning"
							onClick={() => setStep('start')}
						>
							Cancel
						</Button>
					</div>
				)}
			</div>

			{children}
		</div>
	);
}
