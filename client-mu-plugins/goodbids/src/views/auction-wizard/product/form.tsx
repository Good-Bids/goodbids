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
			(category) => category.slug === 'rewards',
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
				Create Auction Reward
			</h1>
			<h2 className="text-admin-large text-admin-main m-0">
				What are you auctioning?
			</h2>

			<form
				id={REWARD_FORM_ID}
				onSubmit={handleSubmit(onSubmit)}
				className="flex flex-col gap-4"
			>
				<div className="flex flex-col gap-4">
					<div className="w-full max-w-80">
						<TextInput
							label="Title"
							tooltip="Product title"
							error={errors.name?.message}
							{...register('name', {
								required: {
									value: true,
									message: 'Title is required',
								},
							})}
						/>
					</div>

					<div className="w-full max-w-60">
						<TextInput
							inputMode="decimal"
							label="Fair Market Value"
							tooltip="The fair market value of your reward"
							startIcon={<MoneyIcon width={16} />}
							error={
								errors.regular_price?.message ||
								getFieldState('regular_price')?.invalid
									? 'Invalid value. Must match format 0.00'
									: undefined
							}
							{...register('regular_price', {
								required: {
									value: true,
									message: 'Fair Market Value is required',
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
					label="What type of product is it?"
					value={productType}
					onValueChange={handleProductTypeChange}
				>
					<RadioItem value="physical">Physical</RadioItem>
					<RadioItem value="digital">Digital</RadioItem>
					<RadioItem value="experience">Experience</RadioItem>
				</RadioInput>
				{productType === 'physical' && (
					<>
						<div className="w-full max-w-80">
							<TextInput
								label="Weight (lbs)"
								tooltip="Product weight in lbs."
								error={
									errors.weight?.message ||
									getFieldState('weight')?.invalid
										? 'Invalid value. Must match format 0.00'
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
									label="Length (in)"
									tooltip="Product length in inches"
									error={
										errors.length?.message ||
										getFieldState('length')?.invalid
											? 'Invalid value. Must match format 0.00'
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
									label="Width (in)"
									tooltip="Product width in inches"
									error={
										errors.width?.message ||
										getFieldState('width')?.invalid
											? 'Invalid value. Must match format 0.00'
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
									label="Height (in)"
									tooltip="Product height in inches"
									error={
										errors.height?.message ||
										getFieldState('height')?.invalid
											? 'Invalid value. Must match format 0.00'
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
							label="Redemption Details for Auction Winner"
							tooltip="Instructions for the auction winner to redeem their reward"
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
									label="Shipping Class"
									tooltip="Determines base shipping cost"
									value={value}
									onValueChange={onChange}
									disabled={
										shippingClasses.status !== 'success'
									}
								>
									<SelectItem value="none">
										No Shipping Class
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
