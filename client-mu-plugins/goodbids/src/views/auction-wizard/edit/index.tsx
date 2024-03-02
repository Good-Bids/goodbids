import { useGetShippingClasses } from '../api/get-shipping-classes';
import { ErrorWrapper } from '../../../components/error';
import { Logo } from '../../../components/logo';
import { ProductForm } from '../components/product-form';
import { Button } from '~/components/button';
import { __ } from '@wordpress/i18n';
import { useGetProduct } from '../api/get-product';
import { useEffect } from 'react';
import { useAuctionWizardState } from '../store';
import { useUpdateProduct } from '../api/update-product';
import { useGetProductCategories } from '../api/get-product-categories';

type EditFlowProps = {
	rewardId: number;
};

export function EditFlow({ rewardId }: EditFlowProps) {
	const { setProduct, product, setProductValue } = useAuctionWizardState();
	const htmlRegex = /(<([^>]+)>)/gi;

	const productCategories = useGetProductCategories();
	const shippingClasses = useGetShippingClasses();
	const getProduct = useGetProduct(rewardId);
	const updateProduct = useUpdateProduct();

	const loading =
		productCategories.status === 'pending' ||
		shippingClasses.status === 'pending' ||
		getProduct.status === 'pending';

	const error =
		productCategories.status === 'error' ||
		shippingClasses.status === 'error' ||
		getProduct.status === 'error';

	useEffect(() => {
		if (getProduct.data) {
			const images = getProduct.data.images.map((image) => {
				return {
					value: {
						id: image.id,
						src: image.src,
					},
				};
			});

			let productType = 'physical';

			if (getProduct.data.purchase_note) {
				productType = 'non-physical';
			}

			setProduct({
				name: { value: getProduct.data.name },
				regularPrice: { value: getProduct.data.regular_price },
				productImage: images[0] ? images[0] : null,
				productGallery: images.length > 1 ? images.slice(1) : [],
				productType: { value: productType },
				weight: { value: getProduct.data.weight },
				length: { value: getProduct.data.dimensions.length },
				width: { value: getProduct.data.dimensions.width },
				height: { value: getProduct.data.dimensions.height },
				purchaseNote: {
					value: getProduct.data.purchase_note.replace(htmlRegex, ''),
				},
				shippingClass: {
					value: getProduct.data.shipping_class || 'none',
				},
			});
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [getProduct.data]);

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

		const category = productCategories.data?.find(
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

	if (loading) {
		return <div>Loading</div>;
	}

	if (error) {
		return <ErrorWrapper>Something went wrong</ErrorWrapper>;
	}

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

			<ProductForm shippingClasses={shippingClasses.data} />

			<div className="w-full flex justify-center">
				<Button variant="solid" onClick={handleSubmit}>
					{__('Save and Continue', 'goodbids')}
				</Button>
			</div>
		</div>
	);
}
