import { useWizardState } from './store';
import { Button } from '../../components/button';
import { Logo } from '../../components/logo';
import { ProgressBar, ProgressBarProps } from '../../components/progress-bar';

type WrapperProps = ProgressBarProps & {
	children: React.ReactNode;
};

export function Wrapper({ children, progress }: WrapperProps) {
	const { step, setStep } = useWizardState();

	const handleBack = () => {
		if (step === 'finish') {
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
		<div className="pr-5 py-3 flex flex-col gap-8">
			<ProgressBar progress={progress} />
			<div className="flex justify-between items-center">
				<Logo />
				{step !== 'start' && <Button onClick={handleBack}>Back</Button>}
			</div>

			{children}
		</div>
	);
}
