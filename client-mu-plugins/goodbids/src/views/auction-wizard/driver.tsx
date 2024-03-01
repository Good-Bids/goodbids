import { Wrapper } from './wrapper';
import { AuctionWizardStart } from './start';
import { StepType, useAuctionWizardState } from './store';
import { AuctionWizardProduct } from './product';
import { useGetShippingClasses } from './api/shipping-classes';
import { useGetProductCategories } from './api/product-categories';
import { AuctionScreen } from './auction';
import { CreateScreen } from './create';
import { FinishScreen } from './finish';
import { ErrorWrapper } from '../../components/error';

const stepProgress: Record<StepType, number> = {
	start: 5,
	product: 33,
	auction: 67,
	create: 90,
	finish: 100,
};

export function Driver() {
	const rewardId = new URLSearchParams(document.location.search).get(
		gbAuctionWizard.editRewardParam,
	);
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

	if (rewardId) {
		return <div>{rewardId}</div>;
	}

	return (
		<Wrapper progress={stepProgress[step]}>
			{(step === 'start' || loading) && (
				<AuctionWizardStart loading={loading} />
			)}

			{step === 'product' && !loading && (
				<AuctionWizardProduct
					shippingClasses={shippingClasses.data || []}
				/>
			)}

			{step === 'auction' && !loading && <AuctionScreen />}

			{step === 'create' && !loading && (
				<CreateScreen
					shippingClasses={shippingClasses.data || []}
					productCategories={productCategories.data || []}
				/>
			)}

			{step === 'finish' && !loading && <FinishScreen />}
		</Wrapper>
	);
}
