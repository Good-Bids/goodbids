import { CreateStoreStep } from './steps/create-store';
import { SetUpPaymentsStep } from './steps/set-up-payments';
import { OnboardingCompleteStep } from './steps/onboarding-complete';
import { z } from 'zod';
import { ActivateAccessibilityCheckerStep } from './steps/activate-accessibility-checker';
import { WelcomeStep } from './steps/welcome';
import { useState } from 'react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

const stepSchema = z
	.enum(gbNonprofitOnboarding.stepOptions)
	.catch('init-onboarding');

export function NonprofitOnboarding() {
	const step = stepSchema.parse(
		new URLSearchParams(document.location.search).get(
			gbNonprofitOnboarding.stepParam,
		),
	);

	if (step === 'init-onboarding') {
		return <WelcomeStep />;
	}

	if (step === 'activate-accessibility-checker') {
		return <ActivateAccessibilityCheckerStep />;
	}

	if (step === 'create-store') {
		return <CreateStoreStep />;
	}

	if (step === 'set-up-payments') {
		return <SetUpPaymentsStep />;
	}

	if (step === 'onboarding-complete') {
		return <OnboardingCompleteStep />;
	}
}

export function Driver() {
	const [queryClient] = useState(() => new QueryClient());

	return (
		<QueryClientProvider client={queryClient}>
			<NonprofitOnboarding />
		</QueryClientProvider>
	);
}
