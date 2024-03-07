import { Button } from '~/components/button';
import { ErrorWrapper } from '~/components/error';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';
import { AuctionForm } from '../components/auction-form';
import { Wrapper } from './wrapper';

export function AuctionStep() {
	const {
		setAuctionValue,
		setStep,
		auction: {
			startDate,
			endDate,
			bidIncrement,
			bidExtensionMinutes,
			error,
		},
	} = useAuctionWizardState();

	const handleNextPage = () => {
		let anyInvalid = false;

		if (!startDate.value) {
			setAuctionValue(
				'startDate',
				'',
				__('Start date is required', 'goodbids'),
			);
			anyInvalid = true;
		}

		if (!endDate.value) {
			setAuctionValue(
				'endDate',
				'',
				__('End date is required', 'goodbids'),
			);
			anyInvalid = true;
		}

		if (!bidIncrement.value) {
			setAuctionValue(
				'bidIncrement',
				'',
				__('Bid increment is required', 'goodbids'),
			);
			anyInvalid = true;
		}

		if (!bidExtensionMinutes.value) {
			setAuctionValue(
				'bidExtensionMinutes',
				'',
				__('Bid extension is required', 'goodbids'),
			);
			anyInvalid = true;
		}

		if (anyInvalid) {
			return;
		}

		setStep('review');
	};

	return (
		<Wrapper
			progress={60}
			step={2}
			title={__('Auction details', 'goodbids')}
		>
			<div className="flex flex-col gap-8">
				{error && <ErrorWrapper>{error}</ErrorWrapper>}

				<AuctionForm />

				<Button variant="solid" onClick={handleNextPage}>
					{__('Save and continue', 'goodbids')}
				</Button>
			</div>
		</Wrapper>
	);
}
