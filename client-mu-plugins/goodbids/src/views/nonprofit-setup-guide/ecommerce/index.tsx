import { __ } from '@wordpress/i18n';
import { Card } from '../components/card';
import { MultiStep } from '../components/multi-step';
import { CardHeading } from '../components/card-heading';
import { ManageShipping } from './manage-shipping';
import { AccessRevenueReport } from './access-revenue-report';
import { ShippingLabels } from './shipping-labels';
import { ManagePayments } from './manage-payments';
import { UpdateWooCommerceStore } from './update-woocommerce-store';

export function Ecommerce() {
	return (
		<Card>
			<CardHeading title={__('Ecommerce', 'goodbids')} />

			<MultiStep
				defaultStep="manage-payments"
				steps={{
					'manage-payments': {
						label: __('Manage Payments', 'goodbids'),
						component: <ManagePayments />,
					},
					'manage-shipping': {
						label: __('Manage Shipping', 'goodbids'),
						component: <ManageShipping />,
					},
					'update-woocommerce-store': {
						label: __('Update WooCommerce Store', 'goodbids'),
						component: <UpdateWooCommerceStore />,
					},
					'access-revenue-report': {
						label: __('Access Revenue Report', 'goodbids'),
						component: <AccessRevenueReport />,
					},
					'shipping-labels': {
						label: __('Shipping Labels', 'goodbids'),
						component: <ShippingLabels />,
					},
				}}
			/>
		</Card>
	);
}
