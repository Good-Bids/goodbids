import clsx from 'clsx';
import { AnimatePresence, motion } from 'framer-motion';
import { useEffect, useState } from 'react';
import { WarningIconFilled } from '../icons/warning-icon-filled';

export const FirstTimeDialog = () => {
	const [hasSeenDialog, setHasSeenDialog] = useState(true);

	useEffect(() => {
		const cookie = localStorage.getItem('gb-has-seen-first-time-dialog');
		if (cookie !== null) {
			const booledCookie = Boolean(cookie);
			setHasSeenDialog(booledCookie);
		} else {
			setHasSeenDialog(false);
		}
	}, []);

	const dialogClasses = clsx(
		'opacity-1 absolute overflow-hidden rounded-xl border-red-50 p-12 text-left shadow-xl sm:my-8 sm:w-[480px] sm:max-w-lg',
	);

	return (
		<AnimatePresence>
			{!hasSeenDialog && (
				<motion.dialog className={dialogClasses} open={!hasSeenDialog}>
					<div className="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
						<div className="flex-col sm:flex sm:items-start">
							<WarningIconFilled />
							<div className="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
								<h3 className="font-bolder text-base leading-6 text-gray-900">
									Every Bid is a Donation
								</h3>
								<div className="mt-2">
									<p className="text-sm text-gray-500">
										Welcome! This is your first bid, and we
										want to remind you that all bids are
										non-refundable donations. Please type
										the word DONATION below to confirm that
										you understand that bids go directly to
										the charity and are non-refundable.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div className="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
						<form onSubmit={() => setHasSeenDialog(true)}>
							<input pattern="DONATION" required />
						</form>
						<button className="bg-green-900 text-white">
							I understand
						</button>
					</div>
				</motion.dialog>
			)}
		</AnimatePresence>
	);
};
