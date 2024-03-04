import { __ } from '@wordpress/i18n';
import { H1, P } from '../../../components/typography';

export function Live() {
	return (
		<>
			<H1>{__('Live', 'goodbids')}</H1>

			<P>
				{__(
					'Your site is live and ready for you to start adding products and auctions!',
					'goobids',
				)}
			</P>
		</>
	);
}
