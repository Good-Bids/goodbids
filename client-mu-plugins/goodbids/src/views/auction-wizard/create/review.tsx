import { Button } from '~/components/button';
import { useCreateAuction } from '../api/create-auction';
import { useCreateProduct } from '../api/create-product';
import { ProductCategories } from '../api/get-product-categories';
import { ShippingClasses } from '../api/get-shipping-classes';
import { useUpdateAuctionContent } from '../api/update-auction-content';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';
import { ReviewAuction } from '../components/review-auction';
import { ReviewProduct } from '../components/reward-product-review';
import { Wrapper } from './wrapper';
import { getContent } from './get-content';

type ReviewStepProps = {
	shippingClasses: ShippingClasses;
	productCategories: ProductCategories;
};

export function ReviewStep({
	shippingClasses,
	productCategories,
}: ReviewStepProps) {
	const {
		product,
		auction,
		setAuctionId,
		setStep,
		clearStore,
		setAuctionError,
		setProductError,
	} = useAuctionWizardState();

	const createProduct = useCreateProduct({
		onSuccess: (data) => {
			handleAuctionSubmit(data.id);
		},
		onError: () => {
			setStep('product');
			setProductError(
				__(
					'Error creating product. Check your product details.',
					'goodbids',
				),
			);
		},
	});

	// We need to create the auction, then, using the auction id,
	// update the auction content with the appropriate id
	const createAuction = useCreateAuction({
		onSuccess: (data) => {
			handleAuctionContentUpdate(data.id);
		},
		onError: () => {
			setStep('auction');
			setAuctionError(
				__(
					'Error creating auction. Check your auction details.',
					'goodbids',
				),
			);
		},
	});

	const updateAuctionContent = useUpdateAuctionContent({
		onSuccess: (data) => {
			completeAuctionWizard(data.id);
		},
		onError: () => {
			setStep('auction');
			setAuctionError(
				__(
					'Error creating auction. Check your auction details.',
					'goodbids',
				),
			);
		},
	});

	const handleSubmitStart = () => {
		const category = productCategories?.find(
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
			createProduct.mutate({
				...base,
				type: 'physical',
				weight: product.weight.value,
				dimensions: {
					length: product.length.value,
					width: product.width.value,
					height: product.height.value,
				},
				shipping_class: product.shippingClass.value,
			});
		} else {
			createProduct.mutate({
				...base,
				type: 'non-physical',
				purchase_note: product.purchaseNote.value,
			});
		}
	};

	const handleAuctionSubmit = (id: number) => {
		createAuction.mutate({
			title: auction.title.value || product.name.value,
			excerpt: auction.excerpt.value,
			acf: {
				auction_start: auction.startDate.value,
				auction_end: auction.endDate.value,
				bid_extension: {
					minutes: parseInt(auction.bidExtensionMinutes.value, 10),
					seconds: 0,
				},
				auction_product: id,
				estimated_value:
					parseInt(auction.estimatedRetailValue.value, 10) || null,
				bid_increment: parseInt(auction.bidIncrement.value, 10),
				starting_bid: auction.startingBid.value
					? parseInt(auction.startingBid.value, 10)
					: parseInt(auction.bidIncrement.value, 10),
				auction_goal: parseInt(auction.auctionGoal.value, 10) || null,
				expected_high_bid:
					parseInt(auction.expectedHighBid.value, 10) || null,
			},
		});
	};

	const handleAuctionContentUpdate = (id: number) => {
		updateAuctionContent.mutate({
			id,
			content: getContent(id),
		});
	};

	const completeAuctionWizard = (id: number) => {
		setAuctionId(id);
		clearStore();
		setStep('finish');
	};

	const loading =
		createProduct.status === 'pending' ||
		createAuction.status === 'pending' ||
		updateAuctionContent.status === 'pending';

	return (
		<Wrapper
			progress={90}
			step={3}
			title={__('Review your auction settings', 'goodbids')}
		>
			<div className="flex flex-col gap-8">
				<ReviewProduct
					shippingClasses={shippingClasses}
					status={createProduct.status}
				/>

				<ReviewAuction
					createStatus={createAuction.status}
					updateStatus={updateAuctionContent.status}
				/>

				<Button
					disabled={loading}
					variant="solid"
					onClick={handleSubmitStart}
				>
					{loading
						? __('Saving', 'goodbids')
						: __('Save my progress', 'goodbids')}
				</Button>
			</div>
		</Wrapper>
	);
}
