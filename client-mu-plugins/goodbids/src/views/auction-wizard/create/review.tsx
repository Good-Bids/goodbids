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
import { ReviewWrapper } from '../components/review-wrapper';

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
		updateAuctionContent.mutate(id);
	};

	const completeAuctionWizard = (id: number) => {
		setAuctionId(id);
		clearStore();
		setStep('finish');
	};

	return (
		<div className="w-full flex flex-col items-center py-10 gap-2">
			<h1 className="text-6xl font-bold text-admin-main m-0">
				{__('Almost there!', 'goodbids')}
			</h1>

			<div className="max-w-xl">
				<p className="text-admin-content">
					{__(
						'Take a moment to review your reward product and auction!',
						'goodbids',
					)}
				</p>
			</div>

			{createProduct.status === 'error' && (
				<span className="text-error-bg text-admin-content">
					{__('Error creating product', 'goodbids')}
				</span>
			)}

			<div className="flex gap-4 items-start w-full justify-center">
				<ReviewWrapper>
					<ReviewProduct
						shippingClasses={shippingClasses}
						status={createProduct.status}
					/>
				</ReviewWrapper>
				<ReviewWrapper>
					<ReviewAuction
						createStatus={createAuction.status}
						updateStatus={updateAuctionContent.status}
					/>
				</ReviewWrapper>
			</div>

			<div className="pt-4">
				<Button variant="solid" onClick={handleSubmitStart}>
					{__('Looks Good!', 'goodbids')}
				</Button>
			</div>
		</div>
	);
}
