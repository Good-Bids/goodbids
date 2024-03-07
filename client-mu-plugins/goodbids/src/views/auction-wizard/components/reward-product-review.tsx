import { useAuctionWizardState } from '../store';
import { ShippingClasses } from '../api/get-shipping-classes';
import { __ } from '@wordpress/i18n';
import { H3 } from '~/components/typography';
import { ReviewStatus } from './review-status';
import { ReviewTable, ReviewTH, ReviewTD } from './review-table';
import { formatStringToCurrency } from '~/utils/number';

type ReviewProductProps = {
	shippingClasses: ShippingClasses;
	status: 'pending' | 'error' | 'success' | 'idle';
};

export function ReviewProduct({ shippingClasses, status }: ReviewProductProps) {
	const { product, setStep } = useAuctionWizardState();

	return (
		<div>
			<H3 as="h2">{__('REWARD', 'goodbids')}</H3>

			<ReviewTable>
				<tr>
					<ReviewTH>{__('Title', 'goodbids')}</ReviewTH>
					<ReviewTD>{product.name.value}</ReviewTD>
				</tr>

				<tr>
					<ReviewTH>{__('Fair market value', 'goodbids')}</ReviewTH>
					<ReviewTD>
						{formatStringToCurrency(product.regularPrice.value)}
					</ReviewTD>
				</tr>

				<tr>
					<ReviewTH>{__('Product category', 'goodbids')}</ReviewTH>
					<ReviewTD>
						{product.productType.value === 'physical'
							? __('Physical', 'goodbids')
							: __('Digital or Experience', 'goodbids')}
					</ReviewTD>
				</tr>

				{product.productType.value === 'physical' ? (
					<>
						{product.weight.value.length > 0 && (
							<tr>
								<ReviewTH>{__('Weight', 'goodbids')}</ReviewTH>
								<ReviewTD>
									{product.weight.value}{' '}
									{__('lbs', 'goodbids')}
								</ReviewTD>
							</tr>
						)}

						{product.length.value.length > 0 &&
							product.width.value.length > 0 &&
							product.height.value.length > 0 && (
								<tr>
									<ReviewTH>
										{__('Size', 'goodbids')}
									</ReviewTH>
									<ReviewTD>
										{product.length.value}{' '}
										{__('in', 'goodbids')} x{' '}
										{product.width.value}{' '}
										{__('in', 'goodbids')} x{' '}
										{product.height.value}{' '}
										{__('in', 'goodbids')}
									</ReviewTD>
								</tr>
							)}
						<tr>
							<ReviewTH>
								{__('Shipping class', 'goodbids')}
							</ReviewTH>
							<ReviewTD>
								{shippingClasses.find(
									(shippingClass) =>
										shippingClass.slug ===
										product.shippingClass.value,
								)?.name || __('None', 'goodbids')}
							</ReviewTD>
						</tr>
					</>
				) : (
					<tr>
						<ReviewTH>
							{__('Redemption details', 'goodbids')}
						</ReviewTH>
						<ReviewTD>{product.purchaseNote.value}</ReviewTD>
					</tr>
				)}
			</ReviewTable>

			<ReviewStatus
				idleText={__('Edit reward', 'goodbids')}
				onClick={() => setStep('product')}
				pendingText={__('Creating reward', 'goodbids')}
				status={status}
				successText={__('Reward created!', 'goodbids')}
			/>
		</div>
	);
}
