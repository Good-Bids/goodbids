import { useBiddingState } from '../../store';
import { WarningIcon } from '../icons/warning-icon';

type ErrorWrapperProps = {
	children: React.ReactNode;
};

export function ErrorWrapper({ children }: ErrorWrapperProps) {
	const { error } = useBiddingState();

	if (error === 'socket') {
		return (
			<>
				{children}
				<ContentWrapper>
					<div className="flex flex-col gap-3">
						<p className="m-0">
							<b>Live auction updates suspended</b>
						</p>
						<p>
							We're having trouble fetching live updates for this
							auctions. Updates will load every 30 seconds until
							the issue is resolved.
						</p>
					</div>
				</ContentWrapper>
			</>
		);
	}

	if (error === 'fetch') {
		return (
			<ContentWrapper>
				<div className="flex flex-col gap-3">
					<p className="m-0">
						<b>Failed to fetch auction data</b>
					</p>
					<p>
						We're having trouble fetching auction data. Please try
						refreshing the page.
					</p>
				</div>
			</ContentWrapper>
		);
	}

	return children;
}

type ContentWrapperProps = {
	children: React.ReactNode;
};

function ContentWrapper({ children }: ContentWrapperProps) {
	return (
		<div className="flex items-start gap-4 bg-warning-bg text-warning-text rounded p-4">
			<div className="h-6 w-6">
				<WarningIcon />
			</div>
			{children}
		</div>
	);
}
