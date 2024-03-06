import { AnimatePresence, motion } from 'framer-motion';
import AuctionStartImage from '../../../../assets/images/auction-start.png';
import { Button } from '~/components/button';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';

type StartStepProps = {
	loading: boolean;
};

export function StartStep({ loading }: StartStepProps) {
	const {
		setStep,
		clearStore,
		product: { name },
		auction: { title },
	} = useAuctionWizardState();

	const setProductStep = () => {
		setStep('product');
	};

	const clearAndSetProductStep = () => {
		clearStore();
		setStep('product');
	};

	return (
		<div className="flex w-full flex-col items-center gap-2 py-10">
			<div>
				<img src={AuctionStartImage} />
			</div>
			<h1 className="m-0 text-6xl font-bold text-admin-main">
				{__('Build an Auction!', 'goodbids')}
			</h1>
			<div className="max-w-xl">
				<p className="text-admin-content">
					{__(
						'This Auction setup will guide you through the required steps for creating and configuring your new Auction. You will be required to create and configure a Product and Auction details. It is recommended to have your product details, descriptions, images, and metadata at hand before starting. You will also be required to set your Auction start and end times, bidding details, and fundraising goals.',
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
						className="mt-4 text-admin-content font-bold text-admin-main"
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
							<h2 className="m-0 text-2xl font-bold text-admin-main">
								{__('Pick up where you left off?', 'goodbids')}
							</h2>

							<Button autoFocus onClick={setProductStep}>
								{__('Continue creating', 'goodbids')}{' '}
								{title.value || name.value}
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
