import { Wrapper } from './wrapper';
import { CreateStoreStep } from './steps/create-store';
import { SetUpPaymentsStep } from './steps/set-up-payments';
import { OnboardingCompleteStep } from './steps/onboarding-complete';
import { z } from 'zod';

const stepSchema = z
	.enum(gbNonprofitOnboarding.steps)
	.catch(gbNonprofitOnboarding.steps[0]);

const stepToProgress = {
	[gbNonprofitOnboarding.steps[0]]: 15,
	[gbNonprofitOnboarding.steps[1]]: 65,
	[gbNonprofitOnboarding.steps[2]]: 100,
};

const stepToComponent = {
	[gbNonprofitOnboarding.steps[0]]: <CreateStoreStep />,
	[gbNonprofitOnboarding.steps[1]]: <SetUpPaymentsStep />,
	[gbNonprofitOnboarding.steps[2]]: <OnboardingCompleteStep />,
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
