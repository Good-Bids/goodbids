import { __ } from '@wordpress/i18n';

export function EditorBidButton() {
	return (
		<span className="btn-fill text-center">
			{__('GOODBID', 'goodbids')} <i>{__('$bid_amount', 'goodbids')}</i>{' '}
			{__('now', 'goodbids')}
		</span>
	);
}
