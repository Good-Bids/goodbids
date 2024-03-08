import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';
import { Footnote } from '../components/footnote';

export function PlanAnAuction() {
	return (
		<MultiStepHeading
			title={__('Plan an Auction', 'goodbids')}
			content={__(
						'Create a GOODBIDS auction using the guided setup wizard, then build and publish the Auction page to your site.',
						'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.auctionWizardURL}
				>
					{__('Get Started', 'goodbids')}
				</ButtonLink>
			</div>

			<Footnote>
				{__('This button takes you to')}{' '}
				<i>{__('Add New', 'goodbids')}</i> {__('in the Auctions tab.')}
			</Footnote>
		</MultiStepHeading>
	);
}
