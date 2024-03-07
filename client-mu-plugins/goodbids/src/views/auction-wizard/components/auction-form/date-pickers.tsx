import { useDebouncedCallback } from 'use-debounce';
import { TextInput } from '~/components/text-input';
import { AuctionState, useAuctionWizardState } from '../../store';
import { validateDateTime } from '~/utils/datetime';
import { __ } from '@wordpress/i18n';
import { H3 } from '~/components/typography';

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
			return __('Start date must be in the future', 'goodbids');
		}

		if (endDate.value) {
			const endDateValue = new Date(endDate.value);

			if (startDateValue >= endDateValue) {
				return __('Start date must be before end date', 'goodbids');
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
			return __('End date must be in the future', 'goodbids');
		}

		if (startDate.value) {
			const startDateValue = new Date(startDate.value);

			if (endDateValue <= startDateValue) {
				return __('End date must be after start date', 'goodbids');
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
			<H3 as="h2">{__('SCHEDULE', 'goodbids')}</H3>

			<div className="grid grid-cols-2 gap-4">
				<TextInput
					label={__('Auction starts', 'goodbids')}
					type="datetime-local"
					id="auction-start"
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
					label={__('Auction ends', 'goodbids')}
					type="datetime-local"
					id="auction-end"
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
