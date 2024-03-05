import { __ } from '@wordpress/i18n';
import { H1, P } from '../../../components/typography';

export function Inactive() {
	return (
		<>
			<H1>{__('Inactive', 'goodbids')}</H1>

			<P>
				{__(
					'Your site is inactive. Contact GOODBIDS support to reactivate your site.',
					'goodbids',
				)}
			</P>
		</>
	);
}
