import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';

export function ManageShipping() {
	return (
		<MultiStepHeading
			title={__('Manage Shipping', 'goodbids')}
			content={
				<>
					{__(
						'To support auctions for physical products, you will need to configure shipping for your WooCommerce store. Create shipping zones for the region(s) you’d like to ship to and shipping method(s) offered. Free shipping, flat rate, and local pickup shipping methods are available. You can also use shipping classes to customize the shipping rates for different groups of products, such as heavy items that require higher postage fees.',
						'goodbids',
					)}{' '}
					<b>
						{__(
							'Note: Flat rate shipping zones must be taxable.',
							'goodbids',
						)}
					</b>
				</>
			}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetup.configureShippingURL}
				>
					{__('Add Shipping Zones', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
