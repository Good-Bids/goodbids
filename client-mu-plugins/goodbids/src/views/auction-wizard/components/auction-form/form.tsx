import { useDebouncedCallback } from 'use-debounce';
import { TextInput } from '~/components/text-input';
import { DatePickers } from './date-pickers';
import { validateDecimal, validateInteger } from '~/utils/number';
import { MoneyIcon } from '~/components/money-icon';
import { Tooltip } from '~/components/tooltip';
import { __ } from '@wordpress/i18n';
import { AuctionState, useAuctionWizardState } from '../../store';
import { TextArea } from '~/components/text-area';

export function Form() {
	const {
		auction: {
			title,
			excerpt,
			bidIncrement,
			startingBid,
			bidExtensionMinutes,
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
				{__('Add Auction Details', 'goodbids')}
			</h1>

			<div className="flex flex-col gap-4">
				<h2 className="text-admin-large text-admin-main m-0">
					{__('Auction Content', 'goodbids')}
				</h2>

				<div className="w-full max-w-80">
					<TextInput
						label={__('Auction Page Title', 'goodbids')}
						id="auction-title"
						tooltip={__(
							'Leave blank to default to Reward Title.',
							'goodbids',
						)}
						defaultValue={title.value}
						onChange={(e) =>
							handleDebounce('title', e.target.value)
						}
					/>
				</div>

				<div className="w-full max-w-120">
					<TextArea
						id="auction-excerpt"
						label={__('Auction Page Excerpt', 'goodbids')}
						tooltip={__(
							'Limit excerpt to 55 words to optimize page layout',
							'goodbids',
						)}
						defaultValue={excerpt.value}
						onChange={(e) =>
							handleDebounce('excerpt', e.target.value)
						}
					/>
				</div>
			</div>

			<DatePickers />

			<div className="flex flex-col gap-4">
				<h2 className="text-admin-large text-admin-main m-0">
					{__('Auction Bidding', 'goodbids')}
				</h2>

				<div className="grid grid-cols-2 items-start gap-4 max-w-120">
					<TextInput
						label={__('Bid Increment', 'goodbids')}
						id="bid-increment"
						tooltip={__(
							'How much the next bid increases by after a bid is placed.',
							'goodbids',
						)}
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
							label={__('Starting Bid', 'goodbids')}
							id="starting-bid"
							tooltip={__('Minimum starting bid.', 'goodbids')}
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
							{__(
								'Leave blank to default the starting bid to the bid increment.',
								'goodbids',
							)}
						</span>
					</div>
				</div>

				<div className="flex items-center gap-4">
					<span className="text-admin-content text-admin-main">
						{__(
							'How long is the bid extension window?',
							'goodbids',
						)}
					</span>
					<Tooltip>
						{__(
							'The auction will end at its scheduled time unless a user places a bid within the bid extension window before the auction closes, which increases the time remaining on the auction.',
							'goodbids',
						)}
					</Tooltip>
				</div>

				<div className="w-full max-w-60">
					<TextInput
						label={__('Minutes', 'goodbids')}
						id="bid-extension-minutes"
						defaultValue={bidExtensionMinutes.value}
						error={bidExtensionMinutes.error}
						inputMode="numeric"
						required
						onChange={(e) =>
							handleDebounce(
								'bidExtensionMinutes',
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
						{__('Auction Fundraising', 'goodbids')}
					</h2>
					<Tooltip>
						{__(
							'These optional values are displayed on the auction page to encourage donations.',
							'goodbids',
						)}
					</Tooltip>
				</div>

				<div className="grid grid-cols-2 items-start gap-4 max-w-120">
					<TextInput
						label={__('Auction Goal', 'goodbids')}
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
						label={__('Expected High Bid', 'goodbids')}
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
						label={__('Estimated Retail Value', 'goodbids')}
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
