import { __ } from '@wordpress/i18n';
import { ButtonLink } from '~/components/button-link';
import { H1, P } from '~/components/typography';
import { PuzzleManImage } from '~/components/images/puzzle-man';
import { Wrapper } from '../wrapper';

export function CreateStoreStep() {
	return (
		<Wrapper progress={33}>
			<PuzzleManImage className="aspect-auto h-50 self-center py-10" />

			<div className="flex flex-col gap-3">
				<H1>{__('Create WooCommerce store', 'goodbids')}</H1>

				<P>
					{__(
						"You'll need to setup WooCommerce on your Nonprofit site so that you can plan and run auctions. Click the button below, then add the U.S. state where your Nonprofit is located. We'll automate the rest of the setup for you!",
						'goodbids',
					)}
				</P>
			</div>

			<ButtonLink
				variant="solid"
				href={gbNonprofitOnboarding.createStoreUrl}
			>
				{__('Add Nonprofit Location', 'goodbids')}
			</ButtonLink>
		</Wrapper>
	);
}
