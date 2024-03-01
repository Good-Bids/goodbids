import { __ } from '@wordpress/i18n';
import { MultiStepExpansion } from '../components/multi-step-expansion';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';

export function PostLaunchTools() {
	return (
		<>
			<MultiStepHeading
				title={__('Review Post-Launch Tools', 'goodbids')}
				content={__(
					'Once your site goes live, you’ll be able to manage auctions, track activity, process rewards, and pay invoices all from your GOODBIDS site. Familiarize yourself with our available tools.',
					'goodbids',
				)}
			/>

			<MultiStepExpansion
				items={[
					{
						title: __('Monitor Auctions', 'goodbids'),
						content: __(
							'You can find key metrics for your Auctions, including Total Raised and High Bid, on the Auctions page. Note that some Auction information is uneditable after an Auction goes live and will require a GOODBIDS Admin override to update.',
							'goodbids',
						),
						component: (
							<div className="w-full max-w-60 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={gbNonprofitSetup.auctionsURL}
								>
									{__('See Auction Metrics', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
					{
						title: __('Track Bids and Rewards', 'goodbids'),
						content: __(
							'Visit the WooCommerce > Analytics > Categories tab to access information about individual Bids placed on Auctions and Rewards claimed. The Bid Category shows a breakdown of Bids per Auction and total donation revenue (Net Sales). The Rewards Category reports both claimed and unclaimed Rewards for Auctions.',
							'goodbids',
						),
						component: (
							<div className="w-full max-w-60 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={gbNonprofitSetup.orderMetricsURL}
								>
									{__('See Order Metrics', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
					{
						title: __('Access Revenue Report', 'goodbids'),
						content: __(
							'Visit the WooCommerce > Analytics > Revenue tab to see how much you’ve raised on the GOODBIDS network. The Net Sales chart reflects all donation revenue from auctions. You can also see Shipping and Taxes charges for Reward orders.',
							'goodbids',
						),
						component: (
							<div className="w-full max-w-60 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={gbNonprofitSetup.revenueMetricsURL}
								>
									{__('See Revenue Metrics', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
					{
						title: __('View and Pay Invoices', 'goodbids'),
						content: __(
							'Visit the Invoices tab to access Auction and Tax invoices payable to GOODBIDS once your Auctions close. Once an Invoice has been generated, you can click “Pay Now” to pay the invoice.',
							'goodbids',
						),
						component: (
							<div className="w-full max-w-60 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={gbNonprofitSetup.invoicesURL}
								>
									{__('See Invoices', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
					{
						title: __('Moderate Comments', 'goodbids'),
						content: __(
							'If you choose to enable comments on your Auction pages, you can view and moderate them from the Comments tab. You will also be emailed when a comment requires moderation. You can manage your comment moderation settings in the Settings > Discussion tab.',
							'goodbids',
						),
						component: (
							<div className="w-full max-w-60 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={gbNonprofitSetup.commentsURL}
								>
									{__('See Comments', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
				]}
			/>
		</>
	);
}
