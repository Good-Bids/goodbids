import { __ } from '@wordpress/i18n';
import { ButtonLink } from '~/components/button-link';
import { H1, P } from '~/components/typography';
import { CoinManImage } from '~/components/images/coin-man';
import { Wrapper } from '../wrapper';
import { useSetStep } from '../api/set-step';

type SetUpPaymentsStepProps = {
	setCompleteStep: () => void;
};

export function SetUpPaymentsStep({ setCompleteStep }: SetUpPaymentsStepProps) {
	const setStep = useSetStep({
		onSuccess: () => {
			setCompleteStep();
		},
	});

	const handleSetStep = () => {
		setStep.mutate({ step: 'onboarding-complete' });
	};

	return (
		<Wrapper progress={75}>
			<CoinManImage className="aspect-auto h-60 py-10" />

			<div className="flex flex-col gap-3">
				<H1>{__('Set up payments', 'goodbids')}</H1>

				<P>
					{__(
						'If you have login credentials for the Nonprofit’s Stripe account, you can connect it now to enable payments on the site. Otherwise, you can skip this step and enable payments after onboarding. Note that payments must be enabled for a site to launch on the GOODBIDS network',
						'goodbids',
					)}
				</P>
			</div>

			<div className="flex w-full flex-col gap-3">
				<ButtonLink
					variant="solid"
					href={gbNonprofitOnboarding.setUpPaymentsUrl}
				>
					{__('Connect Stripe', 'goodbids')}
				</ButtonLink>

				<ButtonLink
					onClick={handleSetStep}
					href={
						gbNonprofitOnboarding.baseUrl +
						'&' +
						gbNonprofitOnboarding.stepParam +
						'=' +
						gbNonprofitOnboarding.stepOptions[3] +
						'&' +
						gbNonprofitOnboarding.skipStepParam +
						'=' +
						gbNonprofitOnboarding.stepOptions[3]
					}
				>
					{__('Skip for now', 'goodbids')}
				</ButtonLink>
			</div>
		</Wrapper>
	);
}
