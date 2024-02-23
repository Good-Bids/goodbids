import { Wrapper } from './wrapper';
import { AuctionWizardStart } from './start';
import { StepType, useAuctionWizardState } from './store';
import { AuctionWizardProduct } from './product';
import { useGetShippingClasses } from './api/shipping-classes';
import { useGetProductCategories } from './api/product-categories';
import { AuctionScreen } from './auction';
import { FinishScreen } from './finish';

const stepProgress: Record<StepType, number> = {
	start: 5,
	product: 33,
	auction: 67,
	finish: 90,
	edit: 50,
};

export function CreateWizard() {
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
		return <div>Something went wrong</div>;
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
			{step === 'finish' && !loading && (
				<FinishScreen
					shippingClasses={shippingClasses.data || []}
					productCategories={productCategories.data || []}
				/>
			)}
		</Wrapper>
	);
}
