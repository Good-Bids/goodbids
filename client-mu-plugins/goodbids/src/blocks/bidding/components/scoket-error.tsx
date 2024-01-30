import { useBiddingState } from '../store';
import { WarningIcon } from './warning-icon';

export function SocketError() {
	const { hasSocketError } = useBiddingState();

	console.log(hasSocketError);

	if (hasSocketError) {
		return (
			<div className="flex items-start gap-4 bg-warning-bg text-warning-text rounded p-4">
				<div className="h-6 w-6">
					<WarningIcon />
				</div>
				<div className="flex flex-col gap-3">
					<p className="m-0">
						<b>Live auction updates suspended</b>
					</p>
					<p>
						We're having trouble fetching live updates for this
						auctions. Updates will load every 30 seconds until the
						issue is resolved.
					</p>
				</div>
			</div>
		);
	}

	return null;
}
