import AuctionStartImage from '../../../../assets/images/auction-start.png';
import { Button } from '../../../components/button';
import { useWizardStore } from '../store';

export function AuctionWizardStart() {
	const {
		setStep,
		clearStore,
		product: { name },
	} = useWizardStore();

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
				{gbAuctionWizard.strings.introHeading}
			</h1>
			<div className="max-w-xl">
				<p className="text-admin-content">
					{gbAuctionWizard.strings.introText}
				</p>
			</div>

			{name.value.length > 0 ? (
				<div className="mt-4 flex flex-col items-center gap-3">
					<h2 className="text-2xl font-bold text-admin-main m-0">
						Pick up where you left off?
					</h2>

					<Button autoFocus onClick={setProductStep}>
						Continue creating {name.value}
					</Button>

					<Button onClick={clearAndSetProductStep}>
						Start a new product and auction
					</Button>
				</div>
			) : (
				<Button onClick={clearAndSetProductStep} autoFocus>
					{gbAuctionWizard.strings.introButtonText}
				</Button>
			)}
		</div>
	);
}
