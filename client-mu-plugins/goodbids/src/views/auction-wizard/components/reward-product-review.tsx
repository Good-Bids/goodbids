import { ProgressIcon } from '~/components/progress-icon';
import { useAuctionWizardState } from '../store';
import { ShippingClasses } from '../api/get-shipping-classes';
import { CheckIcon } from '~/components/check-icon';
import { __ } from '@wordpress/i18n';
import { Button } from '~/components/button';

type ReviewProductProps = {
	shippingClasses: ShippingClasses;
	status: 'pending' | 'error' | 'success' | 'idle';
};

export function ReviewProduct({ shippingClasses, status }: ReviewProductProps) {
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
				{__('Your Reward Product', 'goodbids')}
			</h2>
			<ul>
				<li className="text-admin-content">
					<b>{__('Title.', 'goodbids')}</b> {product.name.value}
				</li>
				<li className="text-admin-content">
					<b>{__('Fair Market Value.', 'goodbids')}</b>{' '}
					{`${product.regularPrice.value}`}
				</li>
				<li className="text-admin-content">
					<b>{__('Product Category.', 'goodbids')}</b>{' '}
					{product.productType.value === 'physical'
						? __('Physical', 'goodbids')
						: __('Digital or Experience', 'goodbids')}
				</li>

				{product.productType.value === 'physical' ? (
					<>
						<li className="text-admin-content">
							<b>{__('Weight.', 'goodbids')}</b>{' '}
							{product.weight.value} lbs.
						</li>
						<li className="text-admin-content">
							<b>{__('Length.', 'goodbids')}</b>{' '}
							{product.length.value} in.
						</li>
						<li className="text-admin-content">
							<b>{__('Width.', 'goodbids')}</b>{' '}
							{product.width.value} in.
						</li>
						<li className="text-admin-content">
							<b>{__('Height.', 'goodbids')}</b>{' '}
							{product.height.value} in.
						</li>
						<li className="text-admin-content">
							<b>{__('Shipping Class.', 'goodbids')}</b>{' '}
							{shippingClasses.find(
								(shippingClass) =>
									shippingClass.slug ===
									product.shippingClass.value,
							)?.name || __('None', 'goodbids')}
						</li>
					</>
				) : (
					<li className="text-admin-content">
						<b>{__('Redemption Details.', 'goodbids')}</b>{' '}
						{product.purchaseNote.value}
					</li>
				)}
			</ul>

			<div className="flex flex-col gap-2">
				<span className="text-admin-content">
					<b>{__('Product Image', 'goodbids')}</b>
				</span>

				{product.productImage ? (
					<img
						src={product.productImage.value.src}
						className="max-w-44 max-h-32"
					/>
				) : (
					<span>{__('No image selected', 'goodbids')}</span>
				)}
			</div>

			<div className="flex flex-col gap-2">
				<span className="text-admin-content">
					<b>{__('Product Gallery', 'goodbids')}</b>
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
					<span>{__('No images selected', 'goodbids')}</span>
				)}
			</div>

			<Button onClick={() => setStep('product')}>
				{__('Edit Reward Product', 'goodbids')}
			</Button>
		</>
	);
}
