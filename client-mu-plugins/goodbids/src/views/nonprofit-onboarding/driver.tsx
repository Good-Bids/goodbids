import { Wrapper } from './wrapper';
import { CreateStoreStep } from './steps/create-store';
import { SetUpPaymentsStep } from './steps/set-up-payments';
import { OnboardingCompleteStep } from './steps/onboarding-complete';
import { z } from 'zod';

const stepSchema = z
	.enum(gbNonprofitOnboarding.stepOptions)
	.catch('create-store');

const stepToProgress = {
	'create-store': 15,
	'set-up-payments': 65,
	'onboarding-complete': 100,
};

const stepToComponent = {
	'create-store': <CreateStoreStep />,
	'set-up-payments': <SetUpPaymentsStep />,
	'onboarding-complete': <OnboardingCompleteStep />,
};

export function Driver() {
	const step = stepSchema.parse(
		new URLSearchParams(document.location.search).get(
			gbNonprofitOnboarding.stepParam,
		),
	);

	return (
		<main className="flex flex-col gap-4 p-2">
			<Wrapper progress={stepToProgress[step]}>
				{stepToComponent[step]}
			</Wrapper>
		</main>
	);
}
