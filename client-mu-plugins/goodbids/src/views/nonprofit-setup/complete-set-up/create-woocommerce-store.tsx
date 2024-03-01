import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';

export function CreateWooCommerceStore() {
	return (
		<MultiStepHeading
			title={__('Create WooCommerce Store', 'goodbids')}
			content={__(
				'Nonprofit Sites on the GOODBIDS network use WooCommerce to manage online transactions for auctions and rewards. Click the link below, then select the country/region of your Nonprofit and click “Go to store”. Return to this setup dashboard for next steps.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetup.createWooCommerceURL}
				>
					{__('Select My Region', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
