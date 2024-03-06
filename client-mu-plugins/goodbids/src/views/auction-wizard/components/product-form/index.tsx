import { Tips } from '~/components/tips';
import { Form, FormProps } from './form';
import { __ } from '@wordpress/i18n';

export type ProductFormProps = FormProps;

export function ProductForm({ shippingClasses }: ProductFormProps) {
	return (
		<div className="flex gap-4 justify-between">
			<Form shippingClasses={shippingClasses} />

			<Tips>
				{__(
					'You can upload multiple images for your product. The "Product Image" image will be used as the main product image.',
					'goodbids',
				)}
			</Tips>
		</div>
	);
}
