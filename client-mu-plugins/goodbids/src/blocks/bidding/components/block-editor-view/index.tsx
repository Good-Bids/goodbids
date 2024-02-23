import { EditorBidButton } from './editor-bid-button';
import { EditorCountdownTimer } from './editor-countdown-timer';
import { EditorFreeBidButton } from './editor-free-bid-button';
import { EditorFreeBidsPromo } from './editor-free-bids-promo';
import { EditorMetrics } from './editor-metrics';
import { EditorParticipationNotice } from './editor-participation-notice';

export function BlockEditorView() {
	return (
		<div className="flex flex-col w-full gap-6 text-md">
			<EditorMetrics />
			<EditorCountdownTimer />
			<EditorBidButton />
			<EditorFreeBidButton />
			<EditorFreeBidsPromo />
			<EditorParticipationNotice />
		</div>
	);
}
