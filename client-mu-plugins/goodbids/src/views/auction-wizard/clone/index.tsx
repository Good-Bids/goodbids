import { useGetShippingClasses } from '../api/get-shipping-classes';
import { useGetProduct } from '../api/get-product';
import { useEffect } from 'react';
import { useAuctionWizardState } from '../store';
import { useGetProductCategories } from '../api/get-product-categories';
import { Loading } from '../components/loading';
import { Error } from '../components/error';
import { Card } from '~/components/card';
import { useGetAuction } from '../api/get-auction';
import { ReviewStep } from './review';
import { ProductStep } from './product';
import { AuctionStep } from './auction';
import { FinishStep } from './finish';
import { __ } from '@wordpress/i18n';

type CloneProps = {
	auctionId: number;
	rewardId: number;
};

export function Clone({ auctionId, rewardId }: CloneProps) {
	return (
		<div className="flex w-full justify-center pt-12">
			<Card>
				<CloneContent auctionId={auctionId} rewardId={rewardId} />
			</Card>
		</div>
	);
}

function CloneContent({ auctionId, rewardId }: CloneProps) {
	const { setAuction, setProduct, step } = useAuctionWizardState();
	const htmlRegex = /(<([^>]+)>)/gi;

	const productCategories = useGetProductCategories();
	const shippingClasses = useGetShippingClasses();
	const getProduct = useGetProduct(rewardId);
	const getAuction = useGetAuction(auctionId);

	const loading =
		productCategories.status === 'pending' ||
		shippingClasses.status === 'pending' ||
		getProduct.status === 'pending' ||
		getAuction.status === 'pending';

	const error =
		productCategories.status === 'error' ||
		shippingClasses.status === 'error' ||
		getProduct.status === 'error' ||
		getAuction.status === 'error';

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

	useEffect(() => {
		if (!getAuction.data) {
			return;
		}

		let startTimeWarning: string | undefined;
		let endTimeWarning: string | undefined;

		if (getAuction.data.acf.auction_start === '') {
			startTimeWarning = __(
				'Cloned auction start date is in the past. Click edit auction to add a new date.',
				'goodbids',
			);
		}

		if (getAuction.data.acf.auction_end === '') {
			endTimeWarning = __(
				'Cloned auction end date is in the past. Click edit auction to add a new date.',
				'goodbids',
			);
		}

		setAuction({
			title: { value: getAuction.data.title.raw },
			excerpt: {
				value: getAuction.data.excerpt.raw,
			},
			startDate: {
				value: getAuction.data.acf.auction_start,
				error: startTimeWarning,
			},
			endDate: {
				value: getAuction.data.acf.auction_end,
				error: endTimeWarning,
			},
			bidIncrement: {
				value: getAuction.data.acf.bid_increment.toString(),
			},
			startingBid: {
				value: getAuction.data.acf.starting_bid.toString(),
			},
			bidExtensionMinutes: {
				value: getAuction.data.acf.bid_extension.minutes.toString(),
			},
			auctionGoal: {
				value: getAuction.data.acf.auction_goal?.toString() || '',
			},
			expectedHighBid: {
				value: getAuction.data.acf.expected_high_bid?.toString() || '',
			},
			estimatedRetailValue: {
				value: getAuction.data.acf.estimated_value?.toString() || '',
			},
			clonedContent: getAuction.data.content.raw,
		});
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [getAuction.data]);

	if (loading) {
		return <Loading />;
	}

	if (error) {
		return <Error />;
	}

	if (step === 'product') {
		return <ProductStep shippingClasses={shippingClasses.data} />;
	}

	if (step === 'auction') {
		return <AuctionStep />;
	}

	if (step === 'finish') {
		return <FinishStep />;
	}

	return (
		<ReviewStep
			auctionId={auctionId}
			shippingClasses={shippingClasses.data}
			productCategories={productCategories.data}
		/>
	);
}
