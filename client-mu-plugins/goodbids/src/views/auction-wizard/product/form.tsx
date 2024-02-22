import { MoneyIcon } from '../../../components/money-icon';
import { RadioInput, RadioItem } from '../../../components/radio-input';
import { TextInput } from '../../../components/text-input';
import { ShippingClasses } from '../api/shipping-classes';
import { TextArea } from '../../../components/text-area';
import { Select, SelectItem } from '../../../components/select';
import { validateDecimal } from '../../../utils/validate-decimal';
import { ProductImage } from './image-upload/product-image';
import { ProductGallery } from './image-upload/product-gallery';
import { AuctionWizardProductState, useAuctionWizardState } from '../store';
import { useDebouncedCallback } from 'use-debounce';

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
				{gbAuctionWizard.strings.createRewardHeading}
			</h1>
			<h2 className="text-admin-large text-admin-main m-0">
				{gbAuctionWizard.strings.createRewardSubheading}
			</h2>

			<div className="flex flex-col gap-4">
				<div className="w-full max-w-80">
					<TextInput
						autoFocus
						label={gbAuctionWizard.strings.productTitle}
						tooltip={gbAuctionWizard.strings.productTitleTooltip}
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
						label={gbAuctionWizard.strings.fairMarketValueLabel}
						tooltip={gbAuctionWizard.strings.fairMarketValueTooltip}
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
				label={gbAuctionWizard.strings.productTypeLabel}
				defaultValue={productType.value}
				onValueChange={(value) => setProductValue('productType', value)}
			>
				<RadioItem value="physical" id="physical">
					{gbAuctionWizard.strings.productTypePhysical}
				</RadioItem>
				<RadioItem value="non-physical" id="non-physical">
					{gbAuctionWizard.strings.productTypeNonPhysical}
				</RadioItem>
			</RadioInput>

			{productType.value === 'physical' ? (
				<>
					<div className="w-full max-w-80">
						<TextInput
							id="product-weight"
							label={gbAuctionWizard.strings.productWeightLabel}
							tooltip={
								gbAuctionWizard.strings.productWeightTooltip
							}
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
								label={
									gbAuctionWizard.strings.productLengthLabel
								}
								tooltip={
									gbAuctionWizard.strings.productLengthTooltip
								}
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
								label={
									gbAuctionWizard.strings.productWidthLabel
								}
								tooltip={
									gbAuctionWizard.strings.productWidthTooltip
								}
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
								label={
									gbAuctionWizard.strings.productHeightLabel
								}
								tooltip={
									gbAuctionWizard.strings.productHeightTooltip
								}
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
						label={gbAuctionWizard.strings.purchaseNoteLabel}
						tooltip={gbAuctionWizard.strings.purchaseNoteTooltip}
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
						label={gbAuctionWizard.strings.shippingClassLabel}
						tooltip={gbAuctionWizard.strings.shippingClassTooltip}
						value={shippingClass.value}
						error={shippingClass.error}
						onValueChange={(value) =>
							setProductValue('shippingClass', value)
						}
					>
						<SelectItem value="none">
							{gbAuctionWizard.strings.shippingClassNone}
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
