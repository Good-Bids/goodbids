import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';
import { Footnote } from '../components/footnote';

export function AccessRevenueReport() {
	return (
		<MultiStepHeading
			title={__('Access Revenue Report', 'goodbids')}
			content={
				<>
					{__(
						'See how much you’ve raised on the GOODBIDS network! The',
						'goodbids',
					)}{' '}
					<i>{__('Net Sales', 'goodbids')}</i>{' '}
					{__(
						'chart reflects all donation revenue from auctions. You can also see Shipping and Taxes charges for Reward orders.',
						'goodbids',
					)}
				</>
			}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.revenueMetricsURL}
				>
					{__('See Revenue Metrics', 'goodbids')}
				</ButtonLink>
			</div>

			<Footnote>
				{__('This button takes you to the', 'goodbids')}{' '}
				<i>{__('WooCommerce > Analytics > Revenue', 'goodbids')}</i>{' '}
				{__('page.', 'goodbids')}
			</Footnote>
		</MultiStepHeading>
	);
}
