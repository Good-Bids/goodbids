import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';

export function ShippingLabels() {
	return (
		<MultiStepHeading
			title={__('Shipping Labels', 'goodbids')}
			content={__(
				'Connect your site to WordPress.com to access automated shipping label printing for Auction rewards. Click the button below, then click “Connect” in the WooCommerce Shipping & Tax promo block.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
<<<<<<<< HEAD:client-mu-plugins/goodbids/src/views/nonprofit-setup-guide/ecommerce/shipping-labels.tsx
					href={gbNonprofitSetup.woocommerceSettingsURL}
========
					href={gbNonprofitSetupGuide.homeURL}
>>>>>>>> dc5c8b64 ([#555] Onboarding Screens (#571)):client-mu-plugins/goodbids/src/views/nonprofit-setup-guide/prepare-for-launch/preview-site.tsx
				>
					{__('Connect to WooCommerce', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
