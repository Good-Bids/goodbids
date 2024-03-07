import { __ } from '@wordpress/i18n';
import { CloseIcon } from '~/components/icons/close-icon';
import { P } from '~/components/typography';

export function Error() {
	return (
		<div className="box-border flex w-full flex-col items-center justify-center gap-4 p-10 text-gb-red-500">
			<CloseIcon width={48} />
			<h2 className="m-0 !text-gb-xl !font-bold text-inherit">
				{__('Something went wrong...')}
			</h2>
			<P>
				{__(
					"We couldn't make a connection. Try again later or reach out to support if the issue persists.",
					'goodbids',
				)}
			</P>
		</div>
	);
}
