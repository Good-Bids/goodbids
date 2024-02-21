import AuctionStartImage from '../../../../assets/images/auction-start.png';
import { Wrapper } from '../components/wrapper';

export function Main() {
	return (
		<Wrapper progress={25}>
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
				<a
					href={gbAuctionWizard.baseURL + 'product'}
					className="py-2 px-6 border border-solid rounded-admin-sm border-admin-main text-admin-main text-admin-content no-underline"
				>
					{gbAuctionWizard.strings.introButtonText}
				</a>
			</div>
		</Wrapper>
	);
}
