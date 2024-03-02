import { MoneyIcon } from '~/components/money-icon';
import { RadioInput, RadioItem } from '~/components/radio-input';
import { TextInput } from '~/components/text-input';
import { ShippingClasses } from '~/views/auction-wizard/api/get-shipping-classes';
import { TextArea } from '~/components/text-area';
import { Select, SelectItem } from '~/components/select';
import { validateDecimal } from '~/utils/number';
import { ProductImage } from './image-upload/product-image';
import { ProductGallery } from './image-upload/product-gallery';
import { useDebouncedCallback } from 'use-debounce';
import { __ } from '@wordpress/i18n';
import {
	AuctionWizardProductState,
	useAuctionWizardState,
} from '~/views/auction-wizard/store';

export type FormProps = {
	shippingClasses: ShippingClasses;
};

export function Form({ shippingClasses }: FormProps) {
	const {
		setProductValue,
		product: {
			name,
			productType,
			regularPrice,
			weight,
			length,
			width,
			height,
			purchaseNote,
			shippingClass,
		},
	} = useAuctionWizardState();

	const handleDebounce = useDebouncedCallback(
		(
			key: keyof AuctionWizardProductState,
			value: string,
			validator?: (value: string) => string | undefined,
		) => {
			if (validator) {
				const error = validator(value);
				if (error) {
					return setProductValue(key, value, error);
				}
			}

			return setProductValue(key, value);
		},
		200,
	);

	return (
		<div className="flex flex-col gap-4">
			<h1 className="text-4xl text-admin-main m-0">
				{__('Create Auction Reward', 'goodbids')}
			</h1>
			<h2 className="text-admin-large text-admin-main m-0">
				{__('What are you auctioning?', 'goodbids')}
			</h2>

			<div className="flex flex-col gap-4">
				<div className="w-full max-w-80">
					<TextInput
						autoFocus
						label={__('Title', 'goodbids')}
						tooltip={__(
							'The name of the product or experience you’re auctioning.',
							'goodbids',
						)}
						onChange={(e) => handleDebounce('name', e.target.value)}
						defaultValue={name.value}
						error={name.error}
						id="product-name"
					/>
				</div>

				<div className="w-full max-w-60">
					<TextInput
						inputMode="decimal"
						id="regular-price"
						label={__('Fair Market Value', 'goodbids')}
						tooltip={__(
							'The fair market value of your reward, not shown to bidders.',
							'goodbids',
						)}
						startIcon={<MoneyIcon width={16} />}
						error={regularPrice.error}
						defaultValue={regularPrice.value}
						onChange={(e) =>
							handleDebounce(
								'regularPrice',
								e.target.value,
								validateDecimal,
							)
						}
					/>
				</div>
			</div>

			<div className="py-6 flex flex-col gap-4">
				<ProductImage />
				<ProductGallery />
			</div>

			<RadioInput
				id="product-type"
				label={__('What type of product is it?', 'goodbids')}
				tooltip={__(
					'Physical products are shipped to the winner or available for local pickup based on the shipping zones you’ve configured for your Nonprofit site. ',
					'goodbids',
				)}
				defaultValue={productType.value}
				onValueChange={(value) => setProductValue('productType', value)}
			>
				<RadioItem value="physical" id="physical">
					{__('Physical', 'goodbids')}
				</RadioItem>
				<RadioItem value="non-physical" id="non-physical">
					{__('Digital or Experience', 'goodbids')}
				</RadioItem>
			</RadioInput>

			{productType.value === 'physical' ? (
				<>
					<div className="w-full max-w-80">
						<TextInput
							id="product-weight"
							label={__('Weight (lbs)', 'goodbids')}
							tooltip={__('Product weight in lbs.', 'goodbids')}
							defaultValue={weight.value}
							onChange={(e) =>
								handleDebounce(
									'weight',
									e.target.value,
									validateDecimal,
								)
							}
							error={weight.error}
						/>
					</div>

					<div className="grid grid-cols-3 gap-2 w-full max-w-120">
						<div className="w-full">
							<TextInput
								id="product-length"
								label={__('Length (in)', 'goodbids')}
								tooltip={__(
									'Product length in inches',
									'goodbids',
								)}
								defaultValue={length.value}
								onChange={(e) =>
									handleDebounce(
										'length',
										e.target.value,
										validateDecimal,
									)
								}
								error={length.error}
							/>
						</div>

						<div className="w-full">
							<TextInput
								id="product-width"
								label={__('Width (in)', 'goodbids')}
								tooltip={__(
									'Product width in inches',
									'goodbids',
								)}
								defaultValue={width.value}
								onChange={(e) =>
									handleDebounce(
										'width',
										e.target.value,
										validateDecimal,
									)
								}
								error={width.error}
							/>
						</div>

						<div className="w-full">
							<TextInput
								id="product-height"
								label={__('Height (in)', 'goodbids')}
								tooltip={__(
									'Product height in inches',
									'goodbids',
								)}
								defaultValue={height.value}
								onChange={(e) =>
									handleDebounce(
										'height',
										e.target.value,
										validateDecimal,
									)
								}
								error={height.error}
							/>
						</div>
					</div>
				</>
			) : (
				<div className="w-full max-w-120">
					<TextArea
						id="purchase-note"
						label={__(
							'Redemption Details for Auction Winner',
							'goodbids',
						)}
						tooltip={__(
							'This note will be shared with the high bidder after they claim the auction reward.',
							'goodbids',
						)}
						defaultValue={purchaseNote.value}
						onChange={(e) =>
							handleDebounce('purchaseNote', e.target.value)
						}
						error={purchaseNote.error}
					/>
				</div>
			)}
			<div className="h-[1px] w-full bg-gray-600/50" />
			{productType.value === 'physical' && (
				<div className="w-full max-w-80 flex flex-col">
					<Select
						id="shipping-class"
						label={__('Shipping Class', 'goodbids')}
						tooltip={__(
							'Used to override base shipping cost for heavy items, etc. ',
							'goodbids',
						)}
						value={shippingClass.value}
						error={shippingClass.error}
						onValueChange={(value) =>
							setProductValue('shippingClass', value)
						}
					>
						<SelectItem value="none">
							{__('No Shipping Class', 'goodbids')}
						</SelectItem>
						{shippingClasses.map((shippingClass) => (
							<SelectItem
								key={shippingClass.id}
								value={shippingClass.slug}
							>
								{shippingClass.name}
							</SelectItem>
						))}
					</Select>
				</div>
			)}
		</div>
	);
}
