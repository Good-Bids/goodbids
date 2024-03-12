import { __ } from '@wordpress/i18n';
import { H1, P } from '~/components/typography';
import { Button } from '~/components/button';
import { usePublishSite } from '../api/publish-site';
import { AnimatePresence, motion } from 'framer-motion';
import { ProgressIcon } from '~/components/icons/progress-icon';
import { CloseIcon } from '~/components/icons/close-icon';
import { CoinManImage } from '~/components/images/coin-man';

type PendingProps = {
	manuallySetToLive: () => void;
};

export function Pending({ manuallySetToLive }: PendingProps) {
	const publishSite = usePublishSite();

	const handlePublishSite = () => {
		publishSite.mutate({ site_id: gbNonprofitSetupGuide.siteId });
	};

	return (
		<>
			<H1>{__('Pending', 'goodbids')}</H1>

			<P>
				{__(
					'Before you launch your site, be sure to confirm your',
					'goodbids',
				)}{' '}
				<a href={gbNonprofitSetupGuide.optionsGeneralURL}>
					{__('site timezone', 'goodbids')}
				</a>
				{__(' and update your', 'goodbids')}{' '}
				<a href={gbNonprofitSetupGuide.customizeHomepageURL}>
					{__('homepage', 'goodbids')}
				</a>
				.{' '}
				{__(
					'If you plan to auction physical products, you will also need to configure',
					'goodbids',
				)}{' '}
				<a href={gbNonprofitSetupGuide.configureShippingURL}>
					{__('shipping zones.')}
				</a>
			</P>
			<P>
				{__(
					'Review the Nonprofit Site Guide for additional instructions.',
					'goodbids',
				)}
			</P>

			<div className="w-full max-w-60">
				<Button variant="solid" onClick={handlePublishSite}>
					{__('Launch Site', 'goodbids')}
				</Button>
			</div>

			<AnimatePresence>
				{publishSite.status !== 'idle' && (
					<motion.div
						layout
						initial={{ opacity: 0 }}
						animate={{ opacity: 1 }}
						exit={{ opacity: 1 }}
						transition={{ ease: 'easeInOut', duration: 0.2 }}
						className="absolute left-0 top-0 flex h-full w-full flex-col items-center gap-4 bg-gb-gray-100 pt-20 text-gb-green-700"
					>
						{publishSite.status === 'pending' && (
							<>
								<ProgressIcon spin width={48} />
								<H1>{__('Launching your site', 'goodbids')}</H1>
							</>
						)}

						{publishSite.status === 'success' && (
							<>
								<CoinManImage className="aspect-auto h-60 py-10" />
								<H1>{__('Site Launched!', 'goodbids')}</H1>
								<P>
									{__(
										'Congratulations, your GOODBIDS Nonprofit Site is live!',
										'goodbids',
									)}
								</P>

								<div className="w-full max-w-60">
									<Button
										variant="solid"
										onClick={manuallySetToLive}
									>
										{__('Return to the guide', 'goodbids')}
									</Button>
								</div>
							</>
						)}

						{publishSite.status === 'error' && (
							<>
								<CloseIcon width={48} />
								<H1>
									{__('Error Launching Site', 'goodbids')}
								</H1>
							</>
						)}
					</motion.div>
				)}
			</AnimatePresence>
		</>
	);
}
