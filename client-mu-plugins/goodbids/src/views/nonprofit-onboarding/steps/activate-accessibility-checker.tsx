import { __ } from '@wordpress/i18n';
import { ButtonLink } from '~/components/button-link';
import { H1, P } from '~/components/typography';
import { PuzzleManImage } from '~/components/images/puzzle-man';
import { Wrapper } from '../wrapper';

export function ActivateAccessibilityCheckerStep() {
	return (
		<Wrapper progress={25}>
			<PuzzleManImage className="aspect-auto h-50 py-10" />

			<div className="flex flex-col gap-3">
				<H1>{__('Activate Accessibility Checker Pro', 'goodbids')}</H1>
				<P>
					{__(
						'All GOODBIDS Nonprofit sites must meet web accessibility guidelines. The Accessibility Checker Pro plugin is required to help you with compliance. Click the button below, then click Activate License to enable the plugin.',
						'goodbids',
					)}{' '}
					<b>
						{__(
							'Reminder: Leave the license field blank to use the GOODBIDS license key.',
							'goodbids',
						)}
					</b>
				</P>
			</div>

			<ButtonLink
				variant="solid"
				href={gbNonprofitOnboarding.accessibilityCheckerUrl}
			>
				{__('Activate Plugin', 'goodbids')}
			</ButtonLink>
		</Wrapper>
	);
}
