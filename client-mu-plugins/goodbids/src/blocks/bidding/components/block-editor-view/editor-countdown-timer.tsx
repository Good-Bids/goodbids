import { __ } from '@wordpress/i18n';
import { ClockIcon } from '../icons/clock-icon';

export function EditorCountdownTimer() {
	return (
		<div className="flex items-center gap-3 px-4">
			<EditorCountdownTimerIcon />
			<EditCountdownTimerContent />
		</div>
	);
}

function EditorCountdownTimerIcon() {
	return (
		<div className="flex items-center">
			<ClockIcon />
		</div>
	);
}

function EditCountdownTimerContent() {
	return (
		<span>
			{__('You will win in', 'goodbids')}{' '}
			<i>{__('bid_extension_window', 'goodbids')}</i>{' '}
			{__('if nobody else bids.', 'goodbids')}
		</span>
	);
}
