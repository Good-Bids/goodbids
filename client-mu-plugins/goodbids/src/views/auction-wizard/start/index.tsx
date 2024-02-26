import AuctionStartImage from '../../../../assets/images/auction-start.png';
import { Button } from '../../../components/button';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';

export function AuctionWizardStart() {
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

			{name.value.length > 0 ? (
				<div className="mt-4 flex flex-col items-center gap-3">
					<h2 className="text-2xl font-bold text-admin-main m-0">
						{__('Pick up where you left off?', 'goodbids')}
					</h2>

					<Button autoFocus onClick={setProductStep}>
						{__('Continue creating', 'goodbids')}
						{name.value}
					</Button>

					<Button onClick={clearAndSetProductStep}>
						{__('Start a new product and auction', 'goodbids')}
					</Button>
				</div>
			) : (
				<Button onClick={clearAndSetProductStep} autoFocus>
					{__("Let's get started", 'goodbids')}
				</Button>
			)}
		</div>
	);
}
