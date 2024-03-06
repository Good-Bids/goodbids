import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';
import { Button } from '~/components/button';
import { ErrorWrapper } from '~/components/error';
import { ProductForm, ProductFormProps } from '../components/product-form';

type ProductStepProps = ProductFormProps;

export function ProductStep({ shippingClasses }: ProductStepProps) {
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

			<ProductForm shippingClasses={shippingClasses} />

			<div className="flex w-full justify-center">
				<Button variant="solid" onClick={handleNextPage}>
					{__('Save and Continue', 'goodbids')}
				</Button>
			</div>
		</>
	);
}
