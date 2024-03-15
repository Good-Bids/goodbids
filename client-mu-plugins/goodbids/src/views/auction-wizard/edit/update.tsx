import { ShippingClasses } from '../api/get-shipping-classes';
import { ErrorWrapper } from '~/components/error';
import { ProductForm } from '../components/product-form';
import { Button } from '~/components/button';
import { __ } from '@wordpress/i18n';
import { useAuctionWizardState } from '../store';
import { useUpdateProduct } from '../api/update-product';
import { ProductCategories } from '../api/get-product-categories';
import { ProgressIcon } from '~/components/icons/progress-icon';
import { H2 } from '~/components/typography';

type UpdateStepProps = {
	rewardId: number;
	shippingClasses: ShippingClasses;
	productCategories: ProductCategories;
};

export function UpdateStep({
	rewardId,
	shippingClasses,
	productCategories,
}: UpdateStepProps) {
	const { product, setProductValue, setStep, clearStore } =
		useAuctionWizardState();

	const updateProduct = useUpdateProduct({
		onSuccess: () => {
			setStep('finish');
			clearStore();
		},
	});

	const handleSubmit = () => {
		let anyInvalid = false;

		if (!product.name.value) {
			setProductValue('name', '', __('Title is required', 'goodbids'));
			anyInvalid = true;
		}

		if (!product.regularPrice.value) {
			setProductValue(
				'regularPrice',
				'',
				__('Fair Market Value is required', 'goodbids'),
			);
			anyInvalid = true;
		}

		if (
			product.productType.value === 'non-physical' &&
			!product.purchaseNote.value
		) {
			setProductValue(
				'purchaseNote',
				'',
				__(
					'Purchase note is required for digital products and experiences',
					'goodbids',
				),
			);
			anyInvalid = true;
		}

		if (anyInvalid) {
			return;
		}

		const category = productCategories.find(
			(category) => category.slug === 'rewards',
		);

		const images = product.productImage
			? [{ id: product.productImage.value.id }].concat(
					product.productGallery.map((image) => {
						return { id: image.value.id };
					}),
				)
			: product.productGallery.map((image) => {
					return { id: image.value.id };
				});

		const base = {
			name: product.name.value,
			regular_price: product.regularPrice.value,
			images,
			categories: [{ id: category!.id }],
		};

		if (product.productType.value === 'physical') {
			updateProduct.mutate({
				id: rewardId,
				body: {
					...base,
					type: 'physical',
					weight: product.weight.value,
					dimensions: {
						length: product.length.value,
						width: product.width.value,
						height: product.height.value,
					},
					shipping_class: product.shippingClass.value,
				},
			});
		} else {
			updateProduct.mutate({
				id: rewardId,
				body: {
					...base,
					type: 'non-physical',
					purchase_note: product.purchaseNote.value,
				},
			});
		}
	};

	return (
		<div className="flex flex-col gap-8 p-10 text-gb-green-900">
			{updateProduct.status === 'error' && (
				<ErrorWrapper>
					{__(
						'Error updating your reward. Check your product details.',
						'goodbids',
					)}
				</ErrorWrapper>
			)}

			<H2 as="h1">{__('Update your reward')}</H2>

			<ProductForm shippingClasses={shippingClasses} />

			<Button variant="solid" onClick={handleSubmit}>
				{updateProduct.status === 'pending' ? (
					<div className="flex items-center justify-center gap-2">
						<ProgressIcon spin />
						<span>{__('Saving your changes', 'goodbids')}</span>
					</div>
				) : (
					__('Save and Update', 'goodbids')
				)}
			</Button>
		</div>
	);
}
