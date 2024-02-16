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
					Build an Auction!
				</h1>
				<div className="max-w-xl">
					<p className="text-admin-content">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit,
						sed do eiusmod tempor incididunt ut labore et dolore
						magna aliqua. Ut enim ad minim veniam, quis nostrud
						exercitation ullamco laboris nisi ut aliquip ex ea
						commodo consequat. Duis aute irure dolor in
						reprehenderit in voluptate velit esse cillum dolore eu
						fugiat nulla pariatur. Excepteur sint occaecat cupidatat
						non proident, sunt in culpa qui officia deserunt mollit
						anim id est laborum.
					</p>
				</div>
				<a
					href="/wp-admin/admin.php?page=gb-auction-wizard-product"
					className="py-2 px-6 border border-solid rounded-admin-sm border-admin-main text-admin-main text-admin-content no-underline"
				>
					Let's get started
				</a>
			</div>
		</Wrapper>
	);
}
