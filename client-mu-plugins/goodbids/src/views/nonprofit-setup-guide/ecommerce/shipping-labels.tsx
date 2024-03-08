import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';
import { Footnote } from '../components/footnote';

export function ShippingLabels() {
	return (
		<MultiStepHeading
			title={__('Shipping Labels', 'goodbids')}
			content={__(
				'Connect your site to WordPress.com to access automated shipping label printing for Auction rewards. Click the button below, then click “Connect” in the WooCommerce Shipping & Tax promo.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.configureShippingURL}
				>
					{__('Connect Site', 'goodbids')}
				</ButtonLink>
			</div>

		    <Footnote>
				{__('This button takes you to the')}{' '}
				<i>{__('WooCommerce > Settings > Shipping', 'goodbids')}</i> {__('page.')}
			</Footnote>
		</MultiStepHeading>
	);
}
