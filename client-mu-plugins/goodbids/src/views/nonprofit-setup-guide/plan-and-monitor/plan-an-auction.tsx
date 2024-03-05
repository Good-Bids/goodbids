import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';

export function PlanAnAuction() {
	return (
		<MultiStepHeading
			title={__('Plan an Auction', 'goodbids')}
			content={
				<>
					{__(
						'Create your first auction using the guided setup wizard, then build and publish the Auction page. Additional auctions can be created in the',
						'goodbids',
					)}{' '}
					<i>{__('Auctions', 'goodbids')}</i>{' '}
					{__('section', 'goodbids')}
				</>
			}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetup.auctionWizardURL}
				>
					{__('Get Started', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
