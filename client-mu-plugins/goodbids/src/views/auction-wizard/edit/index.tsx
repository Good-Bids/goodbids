import { useGetShippingClasses } from '../api/get-shipping-classes';
import { ErrorWrapper } from '../../../components/error';
import { __ } from '@wordpress/i18n';
import { useGetProduct } from '../api/get-product';
import { useEffect } from 'react';
import { useAuctionWizardState } from '../store';
import { useGetProductCategories } from '../api/get-product-categories';
import { ProgressIcon } from '~/components/progress-icon';
import { H1 } from '~/components/typography';
import { FinishStep } from './finish';
import { UpdateStep } from './update';

type EditFlowProps = {
	auctionId: number;
	rewardId: number;
};

export function EditFlow({ auctionId, rewardId }: EditFlowProps) {
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
		return (
			<div className="flex w-full flex-col items-center gap-2 pt-48 text-admin-main">
				<ProgressIcon spin width={48} />
				<H1>{__('Finding your product!', 'goodbids')}</H1>
			</div>
		);
	}

	if (error) {
		return (
			<ErrorWrapper>
				{__('Something went wrong. Try again later.', 'goodbids')}
			</ErrorWrapper>
		);
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
