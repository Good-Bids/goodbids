import { useDebouncedCallback } from 'use-debounce';
import { TextInput } from '~/components/text-input';
import { DatePickers } from './date-pickers';
import { validateDecimal, validateInteger } from '~/utils/number';
import { MoneyIcon } from '~/components/icons/money-icon';
import { __ } from '@wordpress/i18n';
import { AuctionState, useAuctionWizardState } from '../../store';
import { H3, P } from '~/components/typography';
import { TextArea } from '~/components/text-area';

export function AuctionForm() {
	const {
		auction: {
			title,
			bidIncrement,
			startingBid,
			bidExtensionMinutes,
			auctionGoal,
			expectedHighBid,
			estimatedRetailValue,
			excerpt,
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
			<div className="flex flex-col gap-4">
				<H3 as="h2">{__('CONTENT', 'goodbids')}</H3>

				<TextInput
					defaultValue={title.value}
					error={title.error}
					id="auction-title"
					label={__('Auction title', 'goodbids')}
					onChange={(e) => handleDebounce('title', e.target.value)}
					supportingText={__(
						'Leave blank to default to reward title.',
						'goodbids',
					)}
				/>

				<TextArea
					defaultValue={excerpt.value}
					error={excerpt.error}
					id="auction-excerpt"
					label={__('Description', 'goodbids')}
					onChange={(e) => handleDebounce('excerpt', e.target.value)}
					rows={3}
					supportingText={__(
						"This description will appear beneath the auction title on your auction page. Keep it concise; you'll be able to provide more detailed information about your item using the auction page builder before publishing.",
						'goodbids',
					)}
				/>
			</div>

			<DatePickers />

			<div className="flex flex-col gap-4">
				<H3 as="h2">{__('BIDS', 'goodbids')}</H3>

				<div className="grid grid-cols-2 gap-4">
					<TextInput
						defaultValue={startingBid.value}
						error={startingBid.error}
						id="starting-bid"
						inputMode="decimal"
						label={__('Starting Bid', 'goodbids')}
						startAdornment={<MoneyIcon width={16} />}
						onChange={(e) =>
							handleDebounce(
								'startingBid',
								e.target.value,
								validateDecimal,
							)
						}
					/>

					<TextInput
						defaultValue={bidIncrement.value}
						error={bidIncrement.error}
						id="bid-increment"
						inputMode="decimal"
						label={__('Bid increment', 'goodbids')}
						startAdornment={<MoneyIcon width={16} />}
						onChange={(e) =>
							handleDebounce(
								'bidIncrement',
								e.target.value,
								validateDecimal,
							)
						}
					/>
				</div>

				<P>
					{__(
						'The minimum starting bid and bid increment are $5.',
						'goodbids',
					)}
				</P>

				<TextInput
					defaultValue={bidExtensionMinutes.value}
					endAdornment={
						<span className="text-gb-lg">
							{__('min', 'goodbids')}
						</span>
					}
					error={bidExtensionMinutes.error}
					id="bid-extension-minutes"
					inputMode="numeric"
					label={__('Bid extension window', 'goodbids')}
					onChange={(e) =>
						handleDebounce(
							'bidExtensionMinutes',
							e.target.value,
							validateInteger,
						)
					}
					supportingText={__(
						'The auction will extend beyond its scheduled end time with each new bid, based on the bid extension window setting.',
						'goodbids',
					)}
				/>
			</div>

			<div className="flex flex-col gap-4">
				<H3 as="h2">{__('FUNDRAISING INFO (OPTIONAL)', 'goodbids')}</H3>

				<P>
					{__(
						'While these fields are optional, we encourage you to fill them out as this information will be displayed on the auction page, potentially boosting donations.',
						'goodbids',
					)}
				</P>

				<div className="grid grid-cols-2 gap-4">
					<TextInput
						defaultValue={auctionGoal.value}
						error={auctionGoal.error}
						id="auction-goal"
						inputMode="decimal"
						label={__('Auction goal', 'goodbids')}
						onChange={(e) =>
							handleDebounce(
								'auctionGoal',
								e.target.value,
								validateDecimal,
							)
						}
						startAdornment={<MoneyIcon width={16} />}
					/>

					<TextInput
						defaultValue={expectedHighBid.value}
						error={expectedHighBid.error}
						id="expected-high-bid"
						inputMode="decimal"
						label={__('Exp. high bid', 'goodbids')}
						onChange={(e) =>
							handleDebounce(
								'expectedHighBid',
								e.target.value,
								validateDecimal,
							)
						}
						startAdornment={<MoneyIcon width={16} />}
					/>
				</div>

				<TextInput
					defaultValue={estimatedRetailValue.value}
					error={estimatedRetailValue.error}
					id="estimated-retail-value"
					inputMode="decimal"
					label={__('Estimated Retail Value', 'goodbids')}
					onChange={(e) =>
						handleDebounce(
							'estimatedRetailValue',
							e.target.value,
							validateDecimal,
						)
					}
					startAdornment={<MoneyIcon width={16} />}
				/>
			</div>
		</div>
	);
}
