import { __ } from '@wordpress/i18n';
import { H1, P } from '~/components/typography';
import { PuzzleManImage } from '~/components/images/puzzle-man';
import { ButtonLink } from '~/components/button-link';
import { Wrapper } from '../wrapper';

export function WelcomeStep() {
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

			<ButtonLink
				variant="solid"
				href={gbNonprofitOnboarding.initOnboardingUrl}
			>
				{__('Let’s go!', 'goodbids')}
			</ButtonLink>
		</Wrapper>
	);
}
