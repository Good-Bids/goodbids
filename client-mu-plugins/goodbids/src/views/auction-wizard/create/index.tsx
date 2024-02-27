import { Button } from '../../../components/button';
import { useCreateAuction } from '../api/create-auction';
import { useCreateProduct } from '../api/create-product';
import { ProductCategories } from '../api/product-categories';
import { ShippingClasses } from '../api/shipping-classes';
import { useUpdateAuctionContent } from '../api/update-auction-content';
import { useAuctionWizardState } from '../store';
import { Auction } from './auction';
import { ReviewWrapper } from './review-wrapper';
import { RewardProduct } from './reward-product';

type CreateScreenProps = {
	shippingClasses: ShippingClasses;
	productCategories: ProductCategories;
};

export function CreateScreen({
	shippingClasses,
	productCategories,
}: CreateScreenProps) {
	const { product, auction, setAuctionId, setStep, clearStore } =
		useAuctionWizardState();

	const createProduct = useCreateProduct({
		onSuccess: (data) => {
			handleAuctionSubmit(data.id);
		},
	});

	// We need to create the auction, then, using the auction id,
	// update the auction content with the appropriate id
	const createAuction = useCreateAuction({
		onSuccess: (data) => {
			handleAuctionContentUpdate(data.id);
		},
		onError: (error) => {
			console.error(error);
		},
	});

	const updateAuctionContent = useUpdateAuctionContent({
		onSuccess: (data) => {
			completeAuctionWizard(data.id);
		},
		onError: (error) => {
			console.error(error);
		},
	});

	const handleSubmitStart = () => {
		const category = productCategories?.find(
			(category) => category.slug === 'rewards',
		);

		if (!category) {
			throw new Error('No rewards category found');
		}

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
			categories: [{ id: category.id }],
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
			title: product.name.value,
			acf: {
				auction_start: auction.startDate.value,
				auction_end: auction.endDate.value,
				bid_extension: {
					minutes: parseInt(auction.bidExtensionMinutes.value, 10),
					seconds: parseInt(auction.bidExtensionSeconds.value, 10),
				},
				auction_product: id,
				estimated_value: auction.estimatedRetailValue.value,
				bid_increment: parseInt(auction.bidIncrement.value, 10),
				starting_bid: parseInt(auction.startingBid.value, 10),
				auction_goal: auction.auctionGoal.value,
				expected_high_bid: auction.expectedHighBid.value,
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
				Almost there!
			</h1>

			<div className="max-w-xl">
				<p className="text-admin-content">
					Take a moment to review your reward product and auction!
				</p>
			</div>

			{createProduct.status === 'error' && (
				<span className="text-error-bg text-admin-content">
					Error creating product
				</span>
			)}

			<div className="flex gap-4 items-start w-full justify-center">
				<ReviewWrapper>
					<RewardProduct
						shippingClasses={shippingClasses}
						status={createProduct.status}
					/>
				</ReviewWrapper>
				<ReviewWrapper>
					<Auction
						createStatus={createAuction.status}
						updateStatus={updateAuctionContent.status}
					/>
				</ReviewWrapper>
			</div>

			<div className="pt-4">
				<Button variant="solid" onClick={handleSubmitStart}>
					Save and Complete
				</Button>
			</div>
		</div>
	);
}
