import { useGetProductCategories } from '../api/get-product-categories';
import { useGetShippingClasses } from '../api/get-shipping-classes';
import { useAuctionWizardState } from '../store';
import { StartStep } from './start';
import { ProductStep } from './product';
import { AuctionStep } from './auction';
import { ReviewStep } from './review';
import { FinishStep } from './finish';
import { Card } from '../../../components/card';
import { Loading } from '../components/loading';
import { Error } from '../components/error';

export function Create() {
	return (
		<div className="flex w-full justify-center pt-12">
			<Card>
				<CreateContent />
			</Card>
		</div>
	);
}

export function CreateContent() {
	const { step } = useAuctionWizardState();

	const shippingClasses = useGetShippingClasses();
	const productCategories = useGetProductCategories();

	const loading =
		shippingClasses.status === 'pending' ||
		productCategories.status === 'pending';

	const error =
		shippingClasses.status === 'error' ||
		productCategories.status === 'error';

	if (loading) {
		return <Loading />;
	}

	if (error) {
		return <Error />;
	}

	if (step === 'start') {
		return <StartStep />;
	}

	if (step === 'product') {
		return <ProductStep shippingClasses={shippingClasses.data} />;
	}

	if (step === 'auction') {
		return <AuctionStep />;
	}

	if (step === 'review') {
		return (
			<ReviewStep
				shippingClasses={shippingClasses.data}
				productCategories={productCategories.data}
			/>
		);
	}

	if (step === 'finish') {
		return <FinishStep />;
	}
}
