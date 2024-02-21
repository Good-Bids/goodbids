import { useState } from 'react';
import { MoneyIcon } from '../components/money-icon';
import { RadioInput, RadioItem } from '../components/radio-input';
import { TextInput } from '../components/text-input';
import { useGetShippingClasses } from './shipping-classes';
import { TextArea } from '../components/text-area';
import { Select, SelectItem } from '../components/select';
import { SubmitHandler, useForm, Controller } from 'react-hook-form';
import { useGetProductCategories } from './product-categories';
import { ProductType, useCreateProduct } from './create-product';
import { validateDecimal } from './validate-decimal';
import { UploadedImageType } from './image-upload/types';
import { ProductImage } from './image-upload/product-image';
import { ProductGallery } from './image-upload/product-gallery';

type RewardInputs = {
	name: string;
	regular_price: string;
	weight?: string;
	length?: string;
	width?: string;
	height?: string;
	purchase_note?: string;
	shipping_class?: string;
};

export const REWARD_FORM_ID = 'reward-form';

export function Form() {
	const [uploadedProductImage, setUploadedProductImage] =
		useState<UploadedImageType>();

	const [uploadedProductGalleryImages, setUploadedProductGalleryImages] =
		useState<UploadedImageType[]>([]);

	const {
		control,
		handleSubmit,
		register,
		formState: { errors },
		getFieldState,
	} = useForm<RewardInputs>();

	const [productType, setProductType] = useState<ProductType>();

	const shippingClasses = useGetShippingClasses();
	const productCategories = useGetProductCategories();

	const handleProductTypeChange = (value: ProductType) => {
		setProductType(value);
	};

	const createProduct = useCreateProduct({
		// onSuccess: (data) =>
		// 	window.location.assign(
		// 		`/wp-admin/admin.php?page=gb-auction-wizard-auction&reward=${data.id}`,
		// 	),
	});

	const onSubmit: SubmitHandler<RewardInputs> = (data) => {
		const category = productCategories.data?.find(
			(category) => category.slug === gbAuctionWizard.rewardCategorySlug,
		);

		if (!(category && productType)) {
			return;
		}

		const images = uploadedProductImage
			? [{ src: uploadedProductImage.url }].concat(
					uploadedProductGalleryImages.map((image) => ({
						src: image.url,
					})),
				)
			: uploadedProductGalleryImages.map((image) => ({
					src: image.url,
				}));

		const base = {
			name: data.name,
			regular_price: data.regular_price,
			images,
			categories: [{ id: category.id }],
		};

		if (
			productType === 'physical' &&
			data.weight &&
			data.length &&
			data.width &&
			data.height &&
			data.shipping_class
		) {
			createProduct.mutate({
				...base,
				type: productType,
				weight: data.weight,
				dimensions: {
					length: data.length,
					width: data.width,
					height: data.height,
				},
				shipping_class: data.shipping_class,
			});
		} else if (
			(productType === 'digital' || productType === 'experience') &&
			data.purchase_note
		) {
			createProduct.mutate({
				...base,
				type: productType,
				purchase_note: data.purchase_note,
			});
		}
	};

	return (
		<div className="flex flex-col gap-4">
			<h1 className="text-4xl text-admin-main m-0">
				{gbAuctionWizard.strings.createRewardHeading}
			</h1>
			<h2 className="text-admin-large text-admin-main m-0">
				{gbAuctionWizard.strings.createRewardSubheading}
			</h2>

			<form
				id={REWARD_FORM_ID}
				onSubmit={handleSubmit(onSubmit)}
				className="flex flex-col gap-4"
			>
				<div className="flex flex-col gap-4">
					<div className="w-full max-w-80">
						<TextInput
							label={gbAuctionWizard.strings.productTitle}
							tooltip={
								gbAuctionWizard.strings.productTitleTooltip
							}
							error={errors.name?.message}
							{...register('name', {
								required: {
									value: true,
									message:
										gbAuctionWizard.strings
											.productTitleRequired,
								},
							})}
						/>
					</div>

					<div className="w-full max-w-60">
						<TextInput
							inputMode="decimal"
							label={gbAuctionWizard.strings.fairMarketValueLabel}
							tooltip={
								gbAuctionWizard.strings.fairMarketValueTooltip
							}
							startIcon={<MoneyIcon width={16} />}
							error={
								errors.regular_price?.message ||
								getFieldState('regular_price')?.invalid
									? gbAuctionWizard.strings.invalidDecimal
									: undefined
							}
							{...register('regular_price', {
								required: {
									value: true,
									message:
										gbAuctionWizard.strings
											.fairMarketValueRequired,
								},
								validate: (value) => validateDecimal(value),
							})}
						/>
					</div>
				</div>
				<div className="py-6 flex flex-col gap-4">
					<ProductImage
						uploadedProductImage={uploadedProductImage}
						setUploadedProductImage={setUploadedProductImage}
					/>
					<ProductGallery
						uploadedProductGalleryImages={
							uploadedProductGalleryImages
						}
						setUploadedProductGalleryImages={
							setUploadedProductGalleryImages
						}
					/>
				</div>
				<RadioInput
					label={gbAuctionWizard.strings.productTypeLabel}
					value={productType}
					onValueChange={handleProductTypeChange}
					className="cursor-pointer"
				>
					<RadioItem value="physical" className="cursor-pointer">
						{gbAuctionWizard.strings.productTypePhysical}
					</RadioItem>
					<RadioItem value="digital" className="cursor-pointer">
						{gbAuctionWizard.strings.productTypeDigital}
					</RadioItem>
					<RadioItem value="experience" className="cursor-pointer">
						{gbAuctionWizard.strings.productTypeExperience}
					</RadioItem>
				</RadioInput>
				{productType === 'physical' && (
					<>
						<div className="w-full max-w-80">
							<TextInput
								label={
									gbAuctionWizard.strings.productWeightLabel
								}
								tooltip={
									gbAuctionWizard.strings.productWeightTooltip
								}
								error={
									errors.weight?.message ||
									getFieldState('weight')?.invalid
										? gbAuctionWizard.strings.invalidDecimal
										: undefined
								}
								{...register('weight', {
									validate: (value) =>
										value === undefined ||
										validateDecimal(value),
								})}
							/>
						</div>

						<div className="grid grid-cols-3 gap-2 w-full max-w-120">
							<div className="w-full">
								<TextInput
									label={
										gbAuctionWizard.strings
											.productLengthLabel
									}
									tooltip={
										gbAuctionWizard.strings
											.productLengthTooltip
									}
									error={
										errors.length?.message ||
										getFieldState('length')?.invalid
											? gbAuctionWizard.strings
													.invalidDecimal
											: undefined
									}
									{...register('length', {
										validate: (value) =>
											value === undefined ||
											validateDecimal(value),
									})}
								/>
							</div>

							<div className="w-full">
								<TextInput
									label={
										gbAuctionWizard.strings
											.productWidthLabel
									}
									tooltip={
										gbAuctionWizard.strings
											.productWidthTooltip
									}
									error={
										errors.width?.message ||
										getFieldState('width')?.invalid
											? gbAuctionWizard.strings
													.invalidDecimal
											: undefined
									}
									{...register('width', {
										validate: (value) =>
											value === undefined ||
											validateDecimal(value),
									})}
								/>
							</div>

							<div className="w-full">
								<TextInput
									label={
										gbAuctionWizard.strings
											.productHeightLabel
									}
									tooltip={
										gbAuctionWizard.strings
											.productHeightTooltip
									}
									error={
										errors.height?.message ||
										getFieldState('height')?.invalid
											? gbAuctionWizard.strings
													.invalidDecimal
											: undefined
									}
									{...register('height', {
										validate: (value) =>
											value === undefined ||
											validateDecimal(value),
									})}
								/>
							</div>
						</div>
					</>
				)}{' '}
				{(productType === 'digital' ||
					productType === 'experience') && (
					<div className="w-full max-w-120">
						<TextArea
							label={gbAuctionWizard.strings.purchaseNoteLabel}
							tooltip={
								gbAuctionWizard.strings.purchaseNoteTooltip
							}
							{...register('purchase_note')}
						/>
					</div>
				)}
				<div className="h-[1px] w-full bg-gray-600/50" />
				{productType === 'physical' && (
					<div className="w-full max-w-80 flex flex-col">
						<Controller
							control={control}
							name="shipping_class"
							render={({ field: { value, onChange } }) => (
								<Select
									id="shipping-class"
									label={
										gbAuctionWizard.strings
											.shippingClassLabel
									}
									tooltip={
										gbAuctionWizard.strings
											.shippingClassTooltip
									}
									value={value}
									onValueChange={onChange}
									disabled={
										shippingClasses.status !== 'success'
									}
								>
									<SelectItem value="none">
										{
											gbAuctionWizard.strings
												.shippingClassNone
										}
									</SelectItem>
									{shippingClasses.data?.map(
										(shippingClass) => (
											<SelectItem
												key={shippingClass.id}
												value={shippingClass.slug}
											>
												{shippingClass.name}
											</SelectItem>
										),
									)}
								</Select>
							)}
						/>
					</div>
				)}
			</form>
		</div>
	);
}
