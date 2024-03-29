import { AnimatePresence } from 'framer-motion';
import { useEffect, useState } from 'react';

export const FirstTimeDialog = () => {
	const [hasSeenDialog, setHasSeenDialog] = useState(true);

	useEffect(() => {
		const cookie = localStorage.getItem('gb-has-seen-first-time-dialog');
		if (cookie !== null) {
			const booledCookie = Boolean(cookie);
			setHasSeenDialog(booledCookie);
		}
	}, []);

	if (hasSeenDialog === true) {
		return null;
	} else if (hasSeenDialog == false) {
		return (
			<AnimatePresence>
				<dialog className="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
					<div className="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
						<div className="sm:flex sm:items-start">
							<div className="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
								<p
									className="h-6 w-6 text-red-600"
									aria-hidden="true"
								>
									!
								</p>
							</div>
							<div className="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
								<h3 className="text-base font-semibold leading-6 text-gray-900">
									Deactivate account
								</h3>
								<div className="mt-2">
									<p className="text-sm text-gray-500">
										Are you sure you want to deactivate your
										account? All of your data will be
										permanently removed. This action cannot
										be undone.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div className="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
						<form onSubmit={() => setHasSeenDialog(true)}>
							<input pattern="DONATION" />
						</form>
					</div>
				</dialog>
			</AnimatePresence>
		);
	}
};
