import { __ } from '@wordpress/i18n';

export function EditorParticipationNotice() {
	return (
		<div>
			<EditorParticipationNoticeContent />
		</div>
	);
}

function EditorParticipationNoticeContent() {
	return (
		<div className="flex flex-col items-center gap-2">
			<p className="m-0 text-center">
				{__('Join GOODBIDS to bid on this auction.', 'goodbids')}
			</p>
			<p className="m-0 text-center">Every GOODBID is a donation.</p>
		</div>
	);
}
