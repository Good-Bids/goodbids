import { Form, FormProps } from './form';
import { Tips } from '../../../components/tips';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';
import { Button } from '../../../components/button';
import { ErrorWrapper } from '../../../components/error';

type AuctionWizardProductProps = FormProps;

export function AuctionWizardProduct({
	shippingClasses,
}: AuctionWizardProductProps) {
	const {
		setProductValue,
		setStep,
		product: { name, regularPrice, productType, purchaseNote, error },
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
				__(
					'Purchase note is required for digital products and experiences',
					'goodbids',
				),
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
			{error && <ErrorWrapper>{error}</ErrorWrapper>}

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
				<Button variant="solid" onClick={handleNextPage}>
					{__('Save and Continue', 'goodbids')}
				</Button>
			</div>
		</>
	);
}
