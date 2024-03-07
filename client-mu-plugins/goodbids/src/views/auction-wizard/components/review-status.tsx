import { Button } from '~/components/button';
import { CheckIcon } from '~/components/icons/check-icon';
import { ProgressIcon } from '~/components/icons/progress-icon';

type ReviewStatusProps = {
	idleText: string;
	onClick: () => void;
	pendingText: string;
	status: 'pending' | 'error' | 'success' | 'idle';
	successText: string;
};

export function ReviewStatus({
	idleText,
	onClick,
	pendingText,
	status,
	successText,
}: ReviewStatusProps) {
	if (status === 'idle') {
		return (
			<Button
				variant="ghost"
				onClick={onClick}
				className="border-none bg-transparent text-gb-md text-gb-green-700 outline-none hover:underline focus:underline"
			>
				{idleText}
			</Button>
		);
	}

	if (status === 'pending') {
		return (
			<div className="flex w-full justify-center gap-2 text-gb-green-700">
				<ProgressIcon spin />
				<span className="px-6 py-3 text-gb-md">{pendingText}</span>
			</div>
		);
	}

	if (status === 'success') {
		return (
			<div className="flex w-full justify-center gap-2 text-gb-green-700">
				<CheckIcon />
				<span className="px-6 py-3 text-gb-md">{successText}</span>
			</div>
		);
	}
}
