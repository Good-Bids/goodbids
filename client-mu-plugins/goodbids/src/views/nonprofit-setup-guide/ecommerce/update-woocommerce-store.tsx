import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';
import { Footnote } from '../components/footnote';

export function UpdateWooCommerceStore() {
	return (
		<MultiStepHeading
			title={__('Update WooCommerce Store', 'goodbids')}
			content={
				<>
					{__(
						'You can update your Nonprofit location and add your full address using the button below.',
						'goodbids',
					)}{' '}
					<b>
						{__(
							'Note: Additional WooCommerce settings were updated on your behalf to meet GOODBIDS requirements.',
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
					href={gbNonprofitSetupGuide.updateWoocommerceStoreURL}
				>
					{__('Update Store', 'goodbids')}
				</ButtonLink>
			</div>

			<Footnote>
				{__('This button takes you to the', 'goodbids')}{' '}
				<i>{__('WooCommerce > Settings', 'goodbids')}</i>{' '}
				{__('page.', 'goodbids')}
			</Footnote>
		</MultiStepHeading>
	);
}
