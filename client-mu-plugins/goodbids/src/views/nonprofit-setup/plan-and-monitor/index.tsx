import { __ } from '@wordpress/i18n';
import { Card } from '../components/card';
import { MultiStep } from '../components/multi-step';
import { CardHeading } from '../components/card-heading';
import { PlanAnAuction } from './plan-an-auction';
import { MonitorAuctions } from './monitor-auctions';
import { TrackBidsAndRewards } from './track-bids-and-rewards';

export function PlanAndMonitor() {
	return (
		<Card>
			<CardHeading title={__('Plan and Monitor', 'goodbids')} />

			<MultiStep
				defaultStep="plan-an-auction"
				steps={{
					'plan-an-auction': {
						label: __('Plan an Auction', 'goodbids'),
						component: <PlanAnAuction />,
					},
					'monitor-auctions': {
						label: __('Monitor Auctions', 'goodbids'),
						component: <MonitorAuctions />,
					},
					'track-bids-and-rewards': {
						label: __('Track Bids and Rewards', 'goodbids'),
						component: <TrackBidsAndRewards />,
					},
				}}
			/>
		</Card>
	);
}
