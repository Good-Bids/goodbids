import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';

export function MonitorAuctions() {
	return (
		<MultiStepHeading
			title={__('Monitor Auctions', 'goodbids')}
			content={
				<>
					{__(
						'You can find key metrics for your Auctions, including Total Raised and High Bid, on the',
						'goodbids',
					)}{' '}
					<i>{__('Auctions', 'goodbids')}</i>{' '}
					{__(
						'page. Note that some Auction information is uneditable after an Auction goes live and will require a GOODBIDS Admin override to update.',
						'goodbids',
					)}
				</>
			}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetup.auctionsURL}
				>
					{__('See Auction Metrics', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
