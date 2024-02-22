import { useDebouncedCallback } from 'use-debounce';
import { TextInput } from '../../../components/text-input';
import { AuctionState, useAuctionWizardState } from '../store';
import { DatePickers } from './date-pickers';
import { validateDecimal, validateInteger } from '../../../utils/number';
import { MoneyIcon } from '../../../components/money-icon';
import { Tooltip } from '../../../components/tooltip';

export function Form() {
	const {
		auction: {
			bidIncrement,
			startingBid,
			bidExtensionMinutes,
			bidExtensionSeconds,
			auctionGoal,
			expectedHighBid,
			estimatedRetailValue,
		},
		setAuctionValue,
	} = useAuctionWizardState();

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
		<div className="flex flex-col gap-8">
			<h1 className="text-4xl text-admin-main m-0">
				Add Auction Details
			</h1>

			<DatePickers />

			<div className="flex flex-col gap-4">
				<h2 className="text-admin-large text-admin-main m-0">
					Auction Bidding
				</h2>

				<div className="grid grid-cols-2 items-start gap-4 max-w-120">
					<TextInput
						label="Bid Increment"
						id="bid-increment"
						tooltip="How much the next bid increases by after a bid is placed."
						inputMode="decimal"
						startIcon={<MoneyIcon width={16} />}
						required
						defaultValue={bidIncrement.value}
						error={bidIncrement.error}
						onChange={(e) =>
							handleDebounce(
								'bidIncrement',
								e.target.value,
								validateDecimal,
							)
						}
					/>

					<div>
						<TextInput
							label="Starting Bid"
							id="starting-bid"
							tooltip="Minimum starting bid."
							inputMode="decimal"
							startIcon={<MoneyIcon width={16} />}
							defaultValue={startingBid.value}
							error={startingBid.error}
							onChange={(e) =>
								handleDebounce(
									'startingBid',
									e.target.value,
									validateDecimal,
								)
							}
						/>
						<span className="italic px-1">
							Leave blank to default the starting bid to the bid
							increment.
						</span>
					</div>
				</div>

				<div className="flex items-center gap-4">
					<span className="text-admin-content text-admin-main">
						How long is the bid extension window?
					</span>
					<Tooltip>
						The bid extension window increases the time remaining on
						the auction after a user places a bid.
					</Tooltip>
				</div>

				<div className="grid grid-cols-2 items-start gap-4 max-w-120">
					<TextInput
						label="Minutes"
						id="bid-extension-minutes"
						defaultValue={bidExtensionMinutes.value}
						error={bidExtensionMinutes.error}
						inputMode="numeric"
						onChange={(e) =>
							handleDebounce(
								'bidExtensionMinutes',
								e.target.value,
								validateInteger,
							)
						}
					/>

					<TextInput
						label="Seconds"
						id="bid-extension-seconds"
						defaultValue={bidExtensionSeconds.value}
						error={bidExtensionSeconds.error}
						inputMode="numeric"
						onChange={(e) =>
							handleDebounce(
								'bidExtensionSeconds',
								e.target.value,
								validateInteger,
							)
						}
					/>
				</div>
			</div>

			<div className="flex flex-col gap-4">
				<div className="flex items-center gap-4">
					<h2 className="text-admin-large text-admin-main m-0">
						Auction Fundraising
					</h2>
					<Tooltip>
						These values are displayed alongside your auction to
						motivate bidders.
					</Tooltip>
				</div>

				<div className="grid grid-cols-2 items-start gap-4 max-w-120">
					<TextInput
						label="Auction Goal"
						id="auction-goal"
						inputMode="decimal"
						startIcon={<MoneyIcon width={16} />}
						defaultValue={auctionGoal.value}
						error={auctionGoal.error}
						onChange={(e) =>
							handleDebounce(
								'auctionGoal',
								e.target.value,
								validateDecimal,
							)
						}
					/>

					<TextInput
						label="Expected High Bid"
						id="expected-high-bid"
						inputMode="decimal"
						startIcon={<MoneyIcon width={16} />}
						defaultValue={expectedHighBid.value}
						error={expectedHighBid.error}
						onChange={(e) =>
							handleDebounce(
								'expectedHighBid',
								e.target.value,
								validateDecimal,
							)
						}
					/>

					<TextInput
						label="Estimated Retail Value"
						id="estimated-retail-value"
						inputMode="decimal"
						startIcon={<MoneyIcon width={16} />}
						defaultValue={estimatedRetailValue.value}
						error={estimatedRetailValue.error}
						onChange={(e) =>
							handleDebounce(
								'estimatedRetailValue',
								e.target.value,
								validateDecimal,
							)
						}
					/>
				</div>
			</div>
		</div>
	);
}
