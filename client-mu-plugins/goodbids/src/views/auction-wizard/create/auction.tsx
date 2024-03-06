import { Button } from '~/components/button';
import { ErrorWrapper } from '~/components/error';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';
import { AuctionForm } from '../components/auction-form';

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
		<>
			{error && <ErrorWrapper>{error}</ErrorWrapper>}

			<AuctionForm />

			<div className="w-full flex justify-center">
				<Button variant="solid" onClick={handleNextPage}>
					{__('Save and continue', 'goodbids')}
				</Button>
			</div>
		</>
	);
}
