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
				You placed <b>0 bids</b> for a total donation of <b>$0</b>.
			</p>
			<p className="m-0 text-center">Every GOODBID is a donation.</p>
		</div>
	);
}
