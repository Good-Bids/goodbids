import { Button } from '../../../components/button';
import { CheckIcon } from '../../../components/check-icon';
import { ProgressIcon } from '../../../components/progress-icon';
import { ShippingClasses } from '../api/shipping-classes';
import { useAuctionWizardState } from '../store';

type RewardProductProps = {
	shippingClasses: ShippingClasses;
	status: 'pending' | 'error' | 'success' | 'idle';
};

export function RewardProduct({ shippingClasses, status }: RewardProductProps) {
	const { product, setStep } = useAuctionWizardState();

	if (status === 'pending') {
		return (
			<div className="flex flex-col gap-2 items-center">
				<ProgressIcon spin width={48} />
				<h2 className="text-admin-large text-admin-main m-0">
					Saving {product.name.value}
				</h2>
			</div>
		);
	}

	if (status === 'success') {
		return (
			<div className="flex flex-col gap-2 items-center text-admin-main">
				<CheckIcon width={48} />
				<h2 className="text-admin-large text-admin-main m-0">
					{product.name.value} saved!
				</h2>
			</div>
		);
	}

	return (
		<>
			<h2 className="text-admin-large text-admin-main m-0">
				Your Reward Product
			</h2>
			<ul>
				<li className="text-admin-content">
					<b>Title.</b> {product.name.value}
				</li>
				<li className="text-admin-content">
					<b>Fair Market Value.</b> {product.regularPrice.value}
				</li>
				<li className="text-admin-content">
					<b>Product Category.</b>{' '}
					{product.productType.value === 'physical'
						? 'Physical'
						: 'Digital or Experience'}
				</li>

				{product.productType.value === 'physical' ? (
					<>
						<li className="text-admin-content">
							<b>Weight.</b> {product.weight.value} lbs.
						</li>
						<li className="text-admin-content">
							<b>Length.</b> {product.length.value} in.
						</li>
						<li className="text-admin-content">
							<b>Width.</b> {product.width.value} in.
						</li>
						<li className="text-admin-content">
							<b>Height.</b> {product.height.value} in.
						</li>
						<li className="text-admin-content">
							<b>Shipping Class.</b>{' '}
							{shippingClasses.find(
								(shippingClass) =>
									shippingClass.slug ===
									product.shippingClass.value,
							)?.name || 'None'}
						</li>
					</>
				) : (
					<li className="text-admin-content">
						<b>Redemption Details.</b> {product.purchaseNote.value}
					</li>
				)}
			</ul>

			<div className="flex flex-col gap-2">
				<span className="text-admin-content">
					<b>Product Image</b>
				</span>

				{product.productImage ? (
					<img
						src={product.productImage.value.src}
						className="max-w-44 max-h-32"
					/>
				) : (
					<span>No image selected</span>
				)}
			</div>

			<div className="flex flex-col gap-2">
				<span className="text-admin-content">
					<b>Product Gallery</b>
				</span>

				{product.productGallery.length > 0 ? (
					<ul className="flex flex-wrap gap-2 self-center">
						{product.productGallery.map((image) => (
							<li key={image.value.id}>
								<img
									src={image.value.src}
									className="max-w-44 max-h-32"
								/>
							</li>
						))}
					</ul>
				) : (
					<span>No images selected</span>
				)}
			</div>

			<Button onClick={() => setStep('product')}>
				Edit Reward Product
			</Button>
		</>
	);
}
