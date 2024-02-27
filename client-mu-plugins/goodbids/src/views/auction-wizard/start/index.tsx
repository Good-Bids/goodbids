import { AnimatePresence, motion } from 'framer-motion';
import AuctionStartImage from '../../../../assets/images/auction-start.png';
import { Button } from '../../../components/button';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';

type AuctionWizardStartProps = {
	loading: boolean;
};

export function AuctionWizardStart({ loading }: AuctionWizardStartProps) {
	const {
		setStep,
		clearStore,
		product: { name },
	} = useAuctionWizardState();

	const setProductStep = () => {
		setStep('product');
	};

	const clearAndSetProductStep = () => {
		clearStore();
		setStep('product');
	};

	return (
		<div className="w-full flex flex-col items-center py-10 gap-2">
			<div>
				<img src={AuctionStartImage} />
			</div>
			<h1 className="text-6xl font-bold text-admin-main m-0">
				{__('Build an Auction!', 'goodbids')}
			</h1>
			<div className="max-w-xl">
				<p className="text-admin-content">
					{__(
						'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
						'goodbids',
					)}
				</p>
			</div>

			<div className="relative">
				{loading && (
					<motion.span
						initial={{ opacity: 0 }}
						animate={{ opacity: 1 }}
						transition={{ duration: 0.2 }}
						className="mt-4 font-bold text-admin-main text-admin-content"
					>
						Loading...
					</motion.span>
				)}

				<AnimatePresence>
					{!loading && name.value.length > 0 && (
						<motion.div
							initial={{ opacity: 0 }}
							animate={{ opacity: 1 }}
							exit={{ opacity: 0 }}
							transition={{ duration: 0.3 }}
							className="mt-4 flex flex-col items-center gap-3"
						>
							<h2 className="text-2xl font-bold text-admin-main m-0">
								{__('Pick up where you left off?', 'goodbids')}
							</h2>

							<Button autoFocus onClick={setProductStep}>
								{__('Continue creating', 'goodbids')}{' '}
								{name.value}
							</Button>

							<Button onClick={clearAndSetProductStep}>
								{__(
									'Start a new product and auction',
									'goodbids',
								)}
							</Button>
						</motion.div>
					)}
				</AnimatePresence>

				<AnimatePresence>
					{!loading && !name.value.length && (
						<motion.div
							initial={{ opacity: 0 }}
							animate={{ opacity: 1 }}
							exit={{ opacity: 0 }}
							transition={{ duration: 0.3 }}
						>
							<Button onClick={clearAndSetProductStep} autoFocus>
								{__("Let's get started", 'goodbids')}
							</Button>
						</motion.div>
					)}
				</AnimatePresence>
			</div>
		</div>
	);
}
