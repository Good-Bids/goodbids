import { __ } from '@wordpress/i18n';
import { MultiStepExpansion } from '../components/multi-step-expansion';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';

export function ActivateExtensions() {
	return (
		<>
			<MultiStepHeading
				title={__('Activate Extensions', 'goodbids')}
				content={
					<>
						{__(
							'To get the most out of your GOODBIDS Nonprofit Site, it’s recommended that you activate the following extensions. Some extensions require a creating a free third-party account, or connecting your GOODBIDS account to',
							'goodbids',
						)}{' '}
						<a href="https://wordpress.com">
							{__('WordPress.com', 'goodbids')}
						</a>
						.
					</>
				}
			/>

			<MultiStepExpansion
				items={[
					{
						title: __(
							'Security and Performance Monitoring',
							'goodbids',
						),
						content: (
							<>
								{__(
									'Connect your admin account to',
									'goodbids',
								)}{' '}
								<a href="https://wordpress.com">
									{__('WordPress.com', 'goodbids')}
								</a>{' '}
								{__(
									'to enable enhanced security features and access views and visitor metrics for your site.',
									'goodbids',
								)}
							</>
						),
						component: (
							<div className="w-full max-w-80 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={gbNonprofitSetup.jetpackURL}
								>
									{__('Activate Jetpack', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
					{
						title: __('Spam Prevention', 'goodbids'),
						content: __(
							'Set up your Akismet Anti-spam account to enable spam filtering on this site.',
							'goodbids',
						),
						component: (
							<div className="w-full max-w-80 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={gbNonprofitSetup.akismetURL}
								>
									{__('Set Up Akisment Account', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
					{
						title: __('Automated Shipping Labels', 'goodbids'),
						content: (
							<>
								{__('Connect your site to', 'goodbids')}{' '}
								<a href="https://wordpress.com">
									{__('WordPress.com', 'goodbids')}
								</a>{' '}
								{__(
									'to access automated shipping label printing for Auction rewards. Click the button below, then click “Connect” in the WooCommerce Shipping & Tax promo block.',
									'goodbids',
								)}
							</>
						),
						component: (
							<div className="w-full max-w-80 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={
										gbNonprofitSetup.woocommerceSettingsURL
									}
								>
									{__('Connect to WooCommerce', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
				]}
			/>
		</>
	);
}
