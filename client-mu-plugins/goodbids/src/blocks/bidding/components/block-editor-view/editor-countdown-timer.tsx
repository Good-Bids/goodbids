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
			<b>You will win in 30:00</b> if nobody else bids.
		</span>
	);
}
