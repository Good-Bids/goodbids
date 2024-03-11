import { MoneyIcon } from '~/components/icons/money-icon';
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
import { H3 } from '~/components/typography';

export type ProductFormProps = {
	shippingClasses: ShippingClasses;
};

export function ProductForm({ shippingClasses }: ProductFormProps) {
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
		<>
			<TextInput
				autoFocus
				defaultValue={name.value}
				error={name.error}
				id="product-name"
				label={__('Title', 'goodbids')}
				onChange={(e) => handleDebounce('name', e.target.value)}
				placeholder={__(
					'E.g. Bob Dylan’s satin 1970s tour jacket',
					'goodbids',
				)}
				supportingText={__(
					"This is the full name of the product or experience you're auctioning.",
					'goodbids',
				)}
			/>

			<TextInput
				defaultValue={regularPrice.value}
				error={regularPrice.error}
				inputMode="decimal"
				id="regular-price"
				label={__('Fair market value', 'goodbids')}
				onChange={(e) =>
					handleDebounce(
						'regularPrice',
						e.target.value,
						validateDecimal,
					)
				}
				startAdornment={<MoneyIcon width={20} />}
				supportingText={__(
					'This is for internal purposes only and is not shown to bidders.',
					'goodbids',
				)}
			/>

			<ProductImage />

			<ProductGallery />

			<div className="flex flex-col gap-3">
				<H3 as="h2">{__('Reward type', 'goodbids')}</H3>

				<RadioInput
					value={productType.value}
					onValueChange={(value) =>
						setProductValue('productType', value)
					}
				>
					<RadioItem value="physical" id="physical">
						{__('Physical', 'goodbids')}
					</RadioItem>
					<RadioItem value="non-physical" id="non-physical">
						{__('Digital or Experience', 'goodbids')}
					</RadioItem>
				</RadioInput>
			</div>

			{productType.value === 'physical' ? (
				<>
					<div className="grid grid-cols-2 gap-4">
						<TextInput
							defaultValue={weight.value}
							endAdornment={
								<span className="text-gb-lg">
									{__('lbs', 'goodbids')}
								</span>
							}
							error={weight.error}
							id="product-weight"
							label={__('Weight', 'goodbids')}
							onChange={(e) =>
								handleDebounce(
									'weight',
									e.target.value,
									validateDecimal,
								)
							}
						/>

						<TextInput
							defaultValue={length.value}
							endAdornment={
								<span className="text-gb-lg">
									{__('in', 'goodbids')}
								</span>
							}
							error={length.error}
							id="product-length"
							label={__('Length', 'goodbids')}
							onChange={(e) =>
								handleDebounce(
									'length',
									e.target.value,
									validateDecimal,
								)
							}
						/>

						<TextInput
							defaultValue={width.value}
							endAdornment={
								<span className="text-gb-lg">
									{__('in', 'goodbids')}
								</span>
							}
							error={width.error}
							id="product-width"
							label={__('Width', 'goodbids')}
							onChange={(e) =>
								handleDebounce(
									'width',
									e.target.value,
									validateDecimal,
								)
							}
						/>

						<TextInput
							defaultValue={height.value}
							endAdornment={
								<span className="text-gb-lg">
									{__('in', 'goodbids')}
								</span>
							}
							error={height.error}
							id="product-height"
							label={__('Height', 'goodbids')}
							onChange={(e) =>
								handleDebounce(
									'height',
									e.target.value,
									validateDecimal,
								)
							}
						/>
					</div>

					<div className="pt-4">
						<Select
							error={shippingClass.error}
							id="shipping-class"
							label={__('Shipping class', 'goodbids')}
							onValueChange={(value) =>
								setProductValue('shippingClass', value)
							}
							supportingText={__(
								"If you've added optional shipping classes for heavy items, etc. you can select one here to override the base shipping cost.",
								'goodbids',
							)}
							value={shippingClass.value}
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
				</>
			) : (
				<div className="pt-4">
					<TextArea
						defaultValue={purchaseNote.value}
						error={purchaseNote.error}
						id="purchase-note"
						label={__('How to redeem', 'goodbids')}
						onChange={(e) =>
							handleDebounce('purchaseNote', e.target.value)
						}
						supportingText={__(
							'We will share this information with the winner when they will claim the reward.',
							'goodbids',
						)}
					/>
				</div>
			)}
		</>
	);
}
