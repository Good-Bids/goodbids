import { Button } from '../../../components/button';
import { CheckIcon } from '../../../components/check-icon';
import { ProgressIcon } from '../../../components/progress-icon';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';

function formatBidExtension(minutes: string, seconds: string) {
	if (minutes.length && seconds.length) {
		return `${minutes} minutes, ${seconds} seconds`;
	}

	if (minutes.length) {
		return `${minutes} minutes`;
	}

	if (seconds.length) {
		return `${seconds} seconds`;
	}

	return 'None';
}

type AuctionProps = {
	createStatus: string;
	updateStatus: string;
};

export function Auction({ createStatus, updateStatus }: AuctionProps) {
	const { auction, setStep } = useAuctionWizardState();

	if (createStatus === 'pending' || updateStatus === 'pending') {
		return (
			<div className="flex flex-col gap-2 items-center">
				<ProgressIcon spin width={48} />
				<h2 className="text-admin-large text-admin-main m-0">
					{__('Saving Auction', 'goodbids')}
				</h2>
			</div>
		);
	}

	if (createStatus === 'success' && updateStatus === 'success') {
		return (
			<div className="flex flex-col gap-2 items-center text-admin-main">
				<CheckIcon width={48} />
				<h2 className="text-admin-large text-admin-main m-0">
					{__('Auction saved!', 'goodbids')}
				</h2>
			</div>
		);
	}

	return (
		<>
			<h2 className="text-admin-large text-admin-main m-0">
				Your Auction
			</h2>
			<ul>
				<li className="text-admin-content">
					<b>{__('Auction Start.', 'goodbids')}</b>{' '}
					{new Date(auction.startDate.value).toLocaleString()}
				</li>
				<li className="text-admin-content">
					<b>{__('Auction End.', 'goodbids')}</b>{' '}
					{new Date(auction.endDate.value).toLocaleString()}
				</li>
				<li className="text-admin-content">
					<b>{__('Bid Increment.', 'goodbids')}</b> $
					{auction.bidIncrement.value}
				</li>
				<li className="text-admin-content">
					<b>{__('Starting Bid.', 'goodbids')}</b>{' '}
					{auction.startingBid.value
						? `$${auction.startingBid.value}`
						: __('None', 'goodbids')}
				</li>
				<li className="text-admin-content">
					<b>{__('Bid Extension.', 'goodbids')}</b>{' '}
					{formatBidExtension(
						auction.bidExtensionMinutes.value,
						auction.bidExtensionSeconds.value,
					)}
				</li>
				<li className="text-admin-content">
					<b>{__('Auction Goal.')}</b>{' '}
					{auction.auctionGoal.value
						? `$${auction.auctionGoal.value}`
						: __('None', 'goodbids')}
				</li>
				<li className="text-admin-content">
					<b>Expected High Bid.</b>{' '}
					{auction.expectedHighBid.value
						? `$${auction.expectedHighBid.value}`
						: __('None', 'goodbids')}
				</li>
				<li className="text-admin-content">
					<b>Estimated Retail Value.</b>{' '}
					{auction.estimatedRetailValue.value
						? `$${auction.estimatedRetailValue.value}`
						: __('None', 'goodbids')}
				</li>
			</ul>

			<Button onClick={() => setStep('auction')}>
				{__('Edit Auction', 'goodbids')}
			</Button>
		</>
	);
}
