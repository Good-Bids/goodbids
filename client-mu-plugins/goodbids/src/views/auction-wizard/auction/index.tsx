import { Button } from '../../../components/button';
import { ErrorWrapper } from '../../../components/error';
import { Tips } from '../../../components/tips';
import { useAuctionWizardState } from '../store';
import { Form } from './form';
import { __ } from '@wordpress/i18n';

export function AuctionScreen() {
	const {
		setAuctionValue,
		setStep,
		auction: { startDate, endDate, bidIncrement, error },
	} = useAuctionWizardState();

	const handleNextPage = () => {
		let anyInvalid = false;

		if (!startDate.value) {
			setAuctionValue('startDate', '', 'Start date is required');
			anyInvalid = true;
		}

		if (!endDate.value) {
			setAuctionValue('endDate', '', 'End date is required');
			anyInvalid = true;
		}

		if (!bidIncrement.value) {
			setAuctionValue('bidIncrement', '', 'Bid increment is required');
			anyInvalid = true;
		}

		if (anyInvalid) {
			return;
		}

		setStep('create');
	};

	return (
		<>
			{error && <ErrorWrapper>{error}</ErrorWrapper>}

			<div className="flex gap-4 justify-between">
				<Form />

				<Tips>
					<p>
						{__(
							'Consider setting your Auction Start to align with marketing efforts. If you want to start the Auction with a particular starting bid, ensure that you set that here, otherwise the first bid will be your bid increment. To keep an Auction going when late bids are received, consider adding a bid extension window.',
							'goodbids',
						)}
					</p>
				</Tips>
			</div>

			<div className="w-full flex justify-center">
				<Button variant="solid" onClick={handleNextPage}>
					{__('Save and continue', 'goodbids')}
				</Button>
			</div>
		</>
	);
}
