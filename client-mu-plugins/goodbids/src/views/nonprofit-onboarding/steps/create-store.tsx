import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { H1, P } from '../../../components/typography';
import { PuzzleManImage } from '~/components/images/puzzle-man';

export function CreateStoreStep() {
	return (
		<div className="flex flex-col items-center gap-8 p-10">
			<PuzzleManImage className="aspect-auto h-50 self-center py-10" />

			<div className="flex flex-col gap-3">
				<H1>{__('Welcome to GOODBIDS!', 'goodbids')}</H1>

				<P>
					{__(
						'Thanks for joining the GOODBIDS network. We’re so happy to have you! To get started with your new Nonprofit site, we need you to complete just a few required onboarding steps. First, click the button below to automate the setup for your WooCommerce store. It’s that easy!',
						'goodbids',
					)}
				</P>
			</div>

			<ButtonLink
				variant="solid"
				href={gbNonprofitOnboarding.createStoreUrl}
			>
				{__('Create my Store', 'goodbids')}
			</ButtonLink>
		</div>
	);
}
