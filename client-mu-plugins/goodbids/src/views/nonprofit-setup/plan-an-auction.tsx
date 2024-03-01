import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../components/button-link';
import { Card } from './components/card';
import { CardHeading } from './components/card-heading';

export function PlanAnAuction() {
	return (
		<Card>
			<CardHeading
				title={__('Plan an Auction', 'goodbids')}
				content={__(
					'Create your first auction using the guided setup wizard, then build and publish the Auction page. Additional auctions can be created in the Auctions section.',
					'goodbids',
				)}
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
			</CardHeading>
		</Card>
	);
}
