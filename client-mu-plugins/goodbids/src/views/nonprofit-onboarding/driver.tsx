import { __ } from '@wordpress/i18n';
import { H1 } from '../../components/typography';
export function Driver() {
	return (
		<main className="flex flex-col gap-4 p-2">
			<H1>{__('GoodBids Onboarding', 'goodbids')}</H1>
		</main>
	);
}
