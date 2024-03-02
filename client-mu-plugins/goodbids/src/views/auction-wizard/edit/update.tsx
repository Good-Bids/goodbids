import { ShippingClasses } from '../api/get-shipping-classes';
import { ErrorWrapper } from '../../../components/error';
import { Logo } from '../../../components/logo';
import { ProductForm } from '../components/product-form';
import { Button } from '~/components/button';
import { __ } from '@wordpress/i18n';
import { useAuctionWizardState } from '../store';
import { useUpdateProduct } from '../api/update-product';
import { ProductCategories } from '../api/get-product-categories';
import { ProgressIcon } from '~/components/progress-icon';

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
	const { product, setProductValue, setStep } = useAuctionWizardState();

	const updateProduct = useUpdateProduct({
		onSuccess: () => {
			setStep('finish');
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
			? [{ src: product.productImage.value.src }].concat(
					product.productGallery.map((image) => {
						return { src: image.value.src };
					}),
				)
			: product.productGallery.map((image) => {
					return { src: image.value.src };
				});

		/* TODO: Figure out local image upload

		 	Current Error:
		 	"Error getting remote image http://turtles.goodbids.vipdev.lndo.site/wp-content/uploads/sites/3/2024/02/monkeys-6.png. Error: cURL error 7: Failed to connect to turtles.goodbids.vipdev.lndo.site port 80 after 37 ms: Connection refused"
		*/
		if (process.env.NODE_ENV === 'development') {
			console.log(
				'Images cannot be uploaded in development mode. Please use the default product creation form to add images.',
			);
		}

		const base = {
			name: product.name.value,
			regular_price: product.regularPrice.value,
			images: process.env.NODE_ENV === 'development' ? [] : images,
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
		<div className="flex flex-col gap-8">
			<Logo />

			{updateProduct.status === 'error' && (
				<ErrorWrapper>
					{__(
						'Error updating your reward. Check your product details.',
					)}
				</ErrorWrapper>
			)}

			<ProductForm shippingClasses={shippingClasses} />

			<div className="w-full flex justify-center">
				<Button variant="solid" onClick={handleSubmit}>
					{updateProduct.status === 'pending' ? (
						<div className="flex gap-2 items-center justify-center">
							<ProgressIcon spin />
							<span>{__('Saving your changes', 'goodbids')}</span>
						</div>
					) : (
						__('Save and Update', 'goodbids')
					)}
				</Button>
			</div>
		</div>
	);
}
