import { useGetShippingClasses } from '../api/get-shipping-classes';
import { useGetProduct } from '../api/get-product';
import { useEffect } from 'react';
import { useAuctionWizardState } from '../store';
import { useGetProductCategories } from '../api/get-product-categories';
import { FinishStep } from './finish';
import { UpdateStep } from './update';
import { Loading } from '../components/loading';
import { Error } from '../components/error';
import { Card } from '../../../components/card';

type EditProps = {
	auctionId: number;
	rewardId: number;
};

export function Edit({ auctionId, rewardId }: EditProps) {
	return (
		<div className="flex w-full justify-center pt-12">
			<Card>
				<EditContent auctionId={auctionId} rewardId={rewardId} />
			</Card>
		</div>
	);
}

export function EditContent({ auctionId, rewardId }: EditProps) {
	const { setProduct, step } = useAuctionWizardState();
	const htmlRegex = /(<([^>]+)>)/gi;

	const productCategories = useGetProductCategories();
	const shippingClasses = useGetShippingClasses();
	const getProduct = useGetProduct(rewardId);

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

	if (loading) {
		return <Loading />;
	}

	if (error) {
		return <Error />;
	}

	if (step === 'finish') {
		return <FinishStep auctionId={auctionId} />;
	}

	return (
		<UpdateStep
			rewardId={rewardId}
			shippingClasses={shippingClasses.data}
			productCategories={productCategories.data}
		/>
	);
}
