import { Wrapper } from './wrapper';
import { CreateStoreStep } from './steps/create-store';
import { SetUpPaymentsStep } from './steps/set-up-payments';
import { OnboardingCompleteStep } from './steps/onboarding-complete';
import { z } from 'zod';
import { ActivateAccessibilityCheckerStep } from './steps/activate-accessibility-checker';
import { Card } from '../../components/card';

const stepSchema = z
	.enum(gbNonprofitOnboarding.stepOptions)
	.catch('create-store');

const stepToProgress = {
	'init-onboarding': 0,
	'create-store': 10,
	'set-up-payments': 40,
	'activate-accessibility-checker': 70,
	'onboarding-complete': 100,
};

const stepToComponent = {
	'init-onboarding': <CreateStoreStep />,
	'create-store': <CreateStoreStep />,
	'set-up-payments': <SetUpPaymentsStep />,
	'activate-accessibility-checker': <ActivateAccessibilityCheckerStep />,
	'onboarding-complete': <OnboardingCompleteStep />,
};

export function Driver() {
	const step = stepSchema.parse(
		new URLSearchParams(document.location.search).get(
			gbNonprofitOnboarding.stepParam,
		),
	);

	return (
		<div className="flex w-full justify-center pt-12">
			<Card>
				<Wrapper progress={stepToProgress[step]}>
					{stepToComponent[step]}
				</Wrapper>
			</Card>
		</div>
	);
}
