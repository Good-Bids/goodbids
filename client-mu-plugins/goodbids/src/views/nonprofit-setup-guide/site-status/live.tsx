import { __ } from '@wordpress/i18n';
import { H1, P } from '../../../components/typography';

export function Live() {
	return (
		<>
			<H1>{__('Live', 'goodbids')}</H1>

			<P>
				{__('Now that your site is live, review the', 'goodbids')}{' '}
				<a href={gbNonprofitSetupGuide.accessibilityCheckerURL}>
					{__('site-wide accessibility report', 'goodbids')}
				</a>{' '}
				{__(
					'and resolve any issues flagged on your pages.',
					'goodbids',
				)}
				{__(
					'Please refer to the Nonprofit Site Guide for details on managing your site, planning auctions, and getting the most out of the GOODBIDS network!',
					'goodbids',
				)}
			</P>
		</>
	);
}
