import clsx from 'clsx';
import { AnimatePresence, motion } from 'framer-motion';
import { useEffect, useState } from 'react';
import { WarningIconFilled } from '../icons/warning-icon-filled';

export const FirstTimeDialog = () => {
	const [hasSeenDialog, setHasSeenDialog] = useState(true);

	const cookieString = 'gb-has-seen-first-time-dialog';

	useEffect(() => {
		const cookie = localStorage.getItem(cookieString);
		if (cookie !== null) {
			const booledCookie = Boolean(cookie);
			setHasSeenDialog(booledCookie);
		} else {
			setHasSeenDialog(false);
		}
	}, []);

	const handleSuccess = () => {
		setHasSeenDialog(true);
		localStorage.setItem(cookieString, 'true');
	};

	const dialogClasses = clsx(
		'opacity-1 absolute overflow-hidden rounded-xl border-red-50 p-12 text-left shadow-xl sm:my-8 sm:w-[384px] sm:max-w-lg',
	);

	return (
		<AnimatePresence>
			{!hasSeenDialog && (
				<motion.dialog className={dialogClasses} open={!hasSeenDialog}>
					<div className="flex-col sm:flex sm:items-start">
						<WarningIconFilled />
						<div className="mt-3 text-center sm:mt-0 sm:text-left">
							<h3 className="font-bolder text-base text-lg leading-6 text-gray-900">
								Every Bid is a Donation
							</h3>
							<div className="mt-2">
								<p className="text-sm text-gb-green-700">
									Welcome! This is your first bid, and we want
									to remind you that all bids are
									non-refundable donations. Please type the
									word DONATION below to confirm that you
									understand that bids go directly to the
									charity and are non-refundable.
								</p>
							</div>
						</div>
					</div>
					<form
						className="flex w-full flex-col gap-1"
						onSubmit={handleSuccess}
					>
						<input
							required
							pattern="DONATION"
							placeholder="DONATION"
							className="rounded border border-solid border-transparent bg-gray-100 px-6 py-2 leading-normal no-underline focus:outline-dotted focus:outline-1 focus:outline-offset-2"
						/>
						<input
							type="submit"
							className="btn-fill w-full text-md"
							value="I understand"
						></input>
					</form>
				</motion.dialog>
			)}
		</AnimatePresence>
	);
};
