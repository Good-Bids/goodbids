import { Form, FormProps } from './form';
import { Tips } from '../../../components/tips';
import { useWizardState } from '../store';

type AuctionWizardProductProps = FormProps;

export function AuctionWizardProduct({
	shippingClasses,
}: AuctionWizardProductProps) {
	const {
		setProductValue,
		setStep,
		product: { name, regularPrice, productType, purchaseNote },
	} = useWizardState();

	const handleNextPage = () => {
		let anyInvalid = false;

		if (!name.value) {
			setProductValue(
				'name',
				'',
				gbAuctionWizard.strings.productTitleRequired,
			);
			anyInvalid = true;
		}

		if (!regularPrice.value) {
			setProductValue(
				'regularPrice',
				'',
				gbAuctionWizard.strings.fairMarketValueRequired,
			);
			anyInvalid = true;
		}

		if (productType.value === 'non-physical' && !purchaseNote.value) {
			setProductValue(
				'purchaseNote',
				'',
				gbAuctionWizard.strings.purchaseNoteRequired,
			);
			anyInvalid = true;
		}

		if (anyInvalid) {
			return;
		}

		setStep('auction');
	};

	return (
		<>
			<div className="flex gap-4 justify-between">
				<Form shippingClasses={shippingClasses} />

				<Tips>
					<p>{gbAuctionWizard.strings.productTips}</p>
				</Tips>
			</div>

			<div className="w-full flex justify-center">
				<button
					onClick={handleNextPage}
					className="py-2 px-6 cursor-pointer border-none rounded-admin-sm bg-admin-main text-white text-admin-content hover:bg-admin-accent hover:text-black transition-colors focus:outline-opacity-50 focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 w-full max-w-80"
				>
					{gbAuctionWizard.strings.nextButtonText}
				</button>
			</div>
		</>
	);
}
