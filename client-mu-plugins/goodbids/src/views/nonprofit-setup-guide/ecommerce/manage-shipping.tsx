import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';
import { Footnote } from '../components/footnote';

export function ManageShipping() {
	return (
		<MultiStepHeading
			title={__('Manage Shipping', 'goodbids')}
			content={
				<>
					{__(
						'You will need to configure shipping to support auctions for physical products. Create shipping zones for the region(s) you’d like to ship to and shipping method(s) offered. Free shipping, flat rate, and local pickup shipping methods are available. Add shipping classes if you need to customize the shipping rates for different groups of products, such as heavy items that require higher postage fees.',
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
					href={gbNonprofitSetupGuide.configureShippingURL}
				>
					{__('Add Shipping Zones', 'goodbids')}
				</ButtonLink>
			</div>

			<Footnote>
				{__('This button takes you to the')}{' '}
				<i>{__('WooCommerce > Settings > Shipping', 'goodbids')}</i> {__('page.')}
			</Footnote>
		</MultiStepHeading>
	);
}
