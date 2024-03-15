import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';
import { H3 } from '~/components/typography';
import { ReviewStatus } from './review-status';
import { ReviewTable, ReviewTH, ReviewTD } from './review-table';
import { formatStringToCurrency } from '~/utils/number';

type ReviewAuctionProps = {
	createStatus: 'idle' | 'pending' | 'error' | 'success';
	updateStatus: 'idle' | 'pending' | 'error' | 'success';
};

function getStatus(createStatus: string, updateStatus: string) {
	if (createStatus === 'pending' || updateStatus === 'pending') {
		return 'pending';
	}

	if (createStatus === 'error' || updateStatus === 'error') {
		return 'error';
	}

	if (createStatus === 'success' || updateStatus === 'success') {
		return 'success';
	}

	return 'idle';
}

export function ReviewAuction({
	createStatus,
	updateStatus,
}: ReviewAuctionProps) {
	const { auction, setStep } = useAuctionWizardState();

	const status = getStatus(createStatus, updateStatus);

	return (
		<div>
			<H3 as="h2">{__('DETAILS', 'goodbids')}</H3>

			<ReviewTable>
				{auction.title.value && (
					<tr>
						<ReviewTH>{__('Title', 'goodbids')}</ReviewTH>
						<ReviewTD>{auction.title.value}</ReviewTD>
					</tr>
				)}

				{auction.excerpt.value && (
					<tr>
						<ReviewTH>{__('Description', 'goodbids')}</ReviewTH>
						<ReviewTD>{auction.excerpt.value}</ReviewTD>
					</tr>
				)}

				<tr>
					<ReviewTH error={!!auction.startDate.error}>
						{__('Start', 'goodbids')}
					</ReviewTH>
					<ReviewTD error={!!auction.startDate.error}>
						{auction.startDate.error ||
							new Date(auction.startDate.value).toLocaleString()}
					</ReviewTD>
				</tr>

				<tr>
					<ReviewTH error={!!auction.endDate.error}>
						{__('End', 'goodbids')}
					</ReviewTH>
					<ReviewTD error={!!auction.endDate.error}>
						{auction.endDate.error ||
							new Date(auction.endDate.value).toLocaleString()}
					</ReviewTD>
				</tr>

				<tr>
					<ReviewTH>{__('Bid increment', 'goodbids')}</ReviewTH>
					<ReviewTD>
						{formatStringToCurrency(auction.bidIncrement.value)}
					</ReviewTD>
				</tr>

				<tr>
					<ReviewTH>{__('Starting bid', 'goodbids')}</ReviewTH>
					<ReviewTD>
						{auction.startingBid.value
							? `${formatStringToCurrency(auction.startingBid.value)}`
							: `${formatStringToCurrency(auction.bidIncrement.value)}`}
					</ReviewTD>
				</tr>

				<tr>
					<ReviewTH>{__('Bid extension', 'goodbids')}</ReviewTH>
					<ReviewTD>
						{auction.bidExtensionMinutes.value}{' '}
						{__('minutes', 'goodbids')}
					</ReviewTD>
				</tr>

				{auction.auctionGoal.value.length > 0 && (
					<tr>
						<ReviewTH>{__('Goal', 'goodbids')}</ReviewTH>
						<ReviewTD>
							{auction.auctionGoal.value
								? `${formatStringToCurrency(auction.auctionGoal.value)}`
								: __('None', 'goodbids')}
						</ReviewTD>
					</tr>
				)}

				{auction.expectedHighBid.value.length > 0 && (
					<tr>
						<ReviewTH>{__('Exp. high bid', 'goodbids')}</ReviewTH>
						<ReviewTD>
							{auction.expectedHighBid.value
								? `${formatStringToCurrency(auction.expectedHighBid.value)}`
								: __('None', 'goodbids')}
						</ReviewTD>
					</tr>
				)}

				{auction.estimatedRetailValue.value.length > 0 && (
					<tr>
						<ReviewTH>{__('Est. value', 'goodbids')}</ReviewTH>
						<ReviewTD>
							{auction.estimatedRetailValue.value
								? `${formatStringToCurrency(auction.estimatedRetailValue.value)}`
								: __('None', 'goodbids')}
						</ReviewTD>
					</tr>
				)}
			</ReviewTable>

			<ReviewStatus
				idleText={__('Edit auction', 'goodbids')}
				onClick={() => setStep('auction')}
				pendingText={__('Creating auction', 'goodbids')}
				status={status}
				successText={__('Auction created!', 'goodbids')}
			/>
		</div>
	);
}
