import { Form, FormProps } from './form';
import { Tips } from '../../../components/tips';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';

type AuctionWizardProductProps = FormProps;

export function AuctionWizardProduct({
	shippingClasses,
}: AuctionWizardProductProps) {
	const {
		setProductValue,
		setStep,
		product: { name, regularPrice, productType, purchaseNote },
	} = useAuctionWizardState();

	const handleNextPage = () => {
		let anyInvalid = false;

		if (!name.value) {
			setProductValue('name', '', __('Title is required', 'goodbids'));
			anyInvalid = true;
		}

		if (!regularPrice.value) {
			setProductValue(
				'regularPrice',
				'',
				__('Fair Market Value is required', 'goodbids'),
			);
			anyInvalid = true;
		}

		if (productType.value === 'non-physical' && !purchaseNote.value) {
			setProductValue(
				'purchaseNote',
				'',
				__('Click to upload', 'goodbids'),
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
					{__(
						'You can upload multiple images for your product. The "Product Image" image will be used as the main product image.',
						'goodbids',
					)}
				</Tips>
			</div>

			<div className="w-full flex justify-center">
				<button
					onClick={handleNextPage}
					className="py-2 px-6 cursor-pointer border-none rounded-admin-sm bg-admin-main text-white text-admin-content hover:bg-admin-accent hover:text-black transition-colors focus:outline-opacity-50 focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 w-full max-w-80"
				>
					{__('Save and Continue', 'goodbids')}
				</button>
			</div>
		</>
	);
}
