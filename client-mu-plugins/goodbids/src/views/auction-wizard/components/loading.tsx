import { __ } from '@wordpress/i18n';
import { ProgressIcon } from '~/components/icons/progress-icon';
import { H2 } from '~/components/typography';

export function Loading() {
	return (
		<div className="text-bg-green-700 box-border flex w-full flex-col items-center justify-center gap-4 p-10">
			<ProgressIcon spin width={48} />
			<H2>{__('Loading...', 'goodbids')}</H2>
		</div>
	);
}
