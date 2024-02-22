import { useDebouncedCallback } from 'use-debounce';
import { TextInput } from '../../../components/text-input';
import { AuctionState, useAuctionWizardState } from '../store';
import { validateDateTime } from '../../../utils/datetime';

export function DatePickers() {
	const {
		auction: { startDate, endDate },
		setAuctionValue,
	} = useAuctionWizardState();

	const validateStartDate = (value: string) => {
		const validDateError = validateDateTime(value);

		if (validDateError) {
			return validDateError;
		}

		const startDateValue = new Date(value);
		const now = new Date();

		if (startDateValue < now) {
			return 'Start date must be in the future';
		}

		if (endDate.value) {
			const endDateValue = new Date(endDate.value);

			if (startDateValue >= endDateValue) {
				return 'Start date must be before end date';
			}
		}
	};

	const validateEndDate = (value: string) => {
		const validDateError = validateDateTime(value);

		if (validDateError) {
			return validDateError;
		}

		const endDateValue = new Date(value);
		const now = new Date();

		if (endDateValue < now) {
			return 'End date must be in the future';
		}

		if (startDate.value) {
			const startDateValue = new Date(startDate.value);

			if (endDateValue <= startDateValue) {
				return 'End date must be after start date';
			}
		}
	};

	const handleDebounce = useDebouncedCallback(
		(
			key: keyof AuctionState,
			value: string,
			validator?: (value: string) => string | undefined,
		) => {
			if (validator) {
				const error = validator(value);
				if (error) {
					return setAuctionValue(key, value, error);
				}
			}

			return setAuctionValue(key, value);
		},
		200,
	);

	return (
		<div className="flex flex-col gap-4">
			<h2 className="text-admin-large text-admin-main m-0">
				Auction Schedule
			</h2>

			<span className="text-admin-content text-admin-main">
				When will the auction take place?
			</span>

			<div className="grid gap-4 grid-cols-2 max-w-120">
				<TextInput
					label="Auction Start"
					type="datetime-local"
					id="auction-start"
					required
					defaultValue={startDate.value}
					error={startDate.error}
					onChange={(e) =>
						handleDebounce(
							'startDate',
							e.target.value,
							validateStartDate,
						)
					}
				/>

				<TextInput
					label="Auction End"
					type="datetime-local"
					id="auction-end"
					required
					defaultValue={endDate.value}
					error={endDate.error}
					onChange={(e) =>
						handleDebounce(
							'endDate',
							e.target.value,
							validateEndDate,
						)
					}
				/>
			</div>
		</div>
	);
}
