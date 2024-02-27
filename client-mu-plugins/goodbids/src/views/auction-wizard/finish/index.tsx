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
						'Now that you have configured your Product and Auction details, continue editing your Auction page to really make it shine! Consider adding additional content to your Auction page to get potential bidders interested. Once you have completed your edits, publish your Auction!',
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
