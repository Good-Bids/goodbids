import { ErrorWrapper } from '~/components/error';
import { useGetProductCategories } from '../api/get-product-categories';
import { useGetShippingClasses } from '../api/get-shipping-classes';
import { StepType, useAuctionWizardState } from '../store';
import { Wrapper } from './wrapper';
import { StartStep } from './start';
import { ProductStep } from './product';
import { AuctionStep } from './auction';
import { ReviewStep } from './review';
import { FinishStep } from './finish';

const stepProgress: Record<StepType, number> = {
	start: 5,
	product: 33,
	auction: 67,
	review: 90,
	finish: 100,
};

export function Create() {
	const { step } = useAuctionWizardState();

	const shippingClasses = useGetShippingClasses();
	const productCategories = useGetProductCategories();

	const loading =
		shippingClasses.status === 'pending' ||
		productCategories.status === 'pending';

	const error =
		shippingClasses.status === 'error' ||
		productCategories.status === 'error';

	if (error) {
		return <ErrorWrapper>Something went wrong</ErrorWrapper>;
	}

	return (
		<Wrapper progress={stepProgress[step]}>
			{(step === 'start' || loading) && <StartStep loading={loading} />}

			{step === 'product' && !loading && (
				<ProductStep shippingClasses={shippingClasses.data || []} />
			)}

			{step === 'auction' && !loading && <AuctionStep />}

			{step === 'review' && !loading && (
				<ReviewStep
					shippingClasses={shippingClasses.data || []}
					productCategories={productCategories.data || []}
				/>
			)}

			{step === 'finish' && !loading && <FinishStep />}
		</Wrapper>
	);
}
