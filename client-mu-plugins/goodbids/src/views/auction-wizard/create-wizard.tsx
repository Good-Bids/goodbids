import { Wrapper } from './wrapper';
import { AuctionWizardStart } from './start';
import { StepType, useWizardState } from './store';
import { AuctionWizardProduct } from './product';
import { useGetShippingClasses } from './api/shipping-classes';
import { useGetProductCategories } from './api/product-categories';

const stepProgress: Record<StepType, number> = {
	start: 5,
	product: 33,
	auction: 67,
	finish: 100,
	edit: 50,
};

export function CreateWizard() {
	const { step } = useWizardState();

	const shippingClasses = useGetShippingClasses();
	const productCategories = useGetProductCategories();

	if (
		shippingClasses.status === 'error' ||
		productCategories.status === 'error'
	) {
		return <div>Something went wrong</div>;
	}

	return (
		<Wrapper progress={stepProgress[step]}>
			{step === 'start' && <AuctionWizardStart />}
			{step === 'product' && (
				<AuctionWizardProduct
					shippingClasses={shippingClasses.data || []}
				/>
			)}
			{step === 'auction' && <div>Auction</div>}
			{step === 'finish' && <div>Finish</div>}
		</Wrapper>
	);
}
