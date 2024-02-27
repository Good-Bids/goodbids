import AuctionEndImage from '../../../../assets/images/auction-end.png';
import { ButtonLink } from '../../../components/button-link';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';

export function FinishScreen() {
	const { auctionId } = useAuctionWizardState();

	return (
		<div className="w-full flex flex-col items-center py-10 gap-2">
			<div>
				<img src={AuctionEndImage} />
			</div>
			<h1 className="text-6xl font-bold text-admin-main m-0">
				{__('Almost there!', 'goodbids')}
			</h1>
			<div className="max-w-xl">
				<p className="text-admin-content">
					{__(
						'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
						'goodbids',
					)}
				</p>
			</div>

			<ButtonLink
				href={`/wp-admin/post.php?post=${auctionId}&action=edit`}
				autoFocus
			>
				{__('Customize Auction page', 'goodbids')}
			</ButtonLink>
		</div>
	);
}
