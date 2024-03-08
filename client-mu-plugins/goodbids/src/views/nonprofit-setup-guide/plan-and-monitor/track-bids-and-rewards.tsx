import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';

export function TrackBidsAndRewards() {
	return (
		<MultiStepHeading
			title={__('Track Bids and Rewards', 'goodbids')}
			content={
				<>
					{__('Visit the', 'goodbids')}{' '}
					<i>
						{__('WooCommerce > Analytics > Categories', 'goodbids')}
					</i>{' '}
					{__(
						'tab to access information about individual Bids placed on Auctions and Rewards claimed. The Bid Category shows a breakdown of Bids per Auction and total donation revenue (Net Sales). The Rewards Category reports both claimed and unclaimed Rewards for Auctions.',
						'goodbids',
					)}
				</>
			}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.orderMetricsURL}
				>
					{__('See Order Metrics', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
