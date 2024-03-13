import { __ } from '@wordpress/i18n';
import { H1, P } from '../../../components/typography';
import { PuzzleManImage } from '~/components/images/puzzle-man';
import { Button } from '~/components/button';
import { Wrapper } from '../wrapper';
import { useSetStep } from '../api/set-step';
import { ProgressIcon } from '~/components/icons/progress-icon';

type WelcomeStepProps = {
	setAccessibilityStep: () => void;
};

export function WelcomeStep({ setAccessibilityStep }: WelcomeStepProps) {
	const setStep = useSetStep({
		onSuccess: () => {
			setAccessibilityStep();
		},
	});

	const handleSetStep = () => {
		setStep.mutate({ step: 'activate-accessibility-checker' });
	};

	return (
		<Wrapper progress={0}>
			<PuzzleManImage className="aspect-auto h-50 py-10" />

			<div className="flex flex-col gap-3">
				<H1>{__('Welcome to GOODBIDS!', 'goodbids')}</H1>
				<P>
					{__(
						"Thanks for joining the GOODBIDS network. We're so happy to have you! To get started with your new Nonprofit site, we need you to complete just a few required onboarding steps. Ready to get started?",
						'goodbids',
					)}
				</P>
			</div>

			<Button variant="solid" onClick={handleSetStep}>
				{setStep.status === 'pending' ? (
					<div className="flex w-full justify-center gap-2">
						<ProgressIcon spin />
						{__('Setting up!', 'goodbids')}
					</div>
				) : (
					__('Let’s go!', 'goodbids')
				)}
			</Button>
		</Wrapper>
	);
}
