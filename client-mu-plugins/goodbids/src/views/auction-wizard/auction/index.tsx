import { Button } from '../../../components/button';
import { Tips } from '../../../components/tips';
import { useAuctionWizardState } from '../store';
import { Form } from './form';

export function AuctionScreen() {
	const {
		setAuctionValue,
		setStep,
		auction: { startDate, endDate, bidIncrement },
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
			<div className="flex gap-4 justify-between">
				<Form />

				<Tips>
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit,
						sed do eiusmod tempor incididunt ut labore et dolore
						magna aliqua. Ut enim ad minim veniam, quis nostrud
						exercitation ullamco laboris nisi ut aliquip ex ea
						commodo consequat. Duis aute irure dolor in
						reprehenderit in voluptate velit esse cillum dolore eu
						fugiat nulla pariatur. Excepteur sint occaecat cupidatat
						non proident, sunt in culpa qui officia deserunt mollit
						anim id est laborum.
					</p>
				</Tips>
			</div>

			<div className="w-full flex justify-center">
				<Button variant="solid" onClick={handleNextPage}>
					Save and continue
				</Button>
			</div>
		</>
	);
}
