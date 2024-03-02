import AuctionEndImage from '../../../../assets/images/auction-end.png';
import { ButtonLink } from '~/components/button-link';
import { __ } from '@wordpress/i18n';

type FinishStepProps = {
	auctionId: number;
};

export function FinishStep({ auctionId }: FinishStepProps) {
	return (
		<div className="w-full flex flex-col items-center py-10 gap-2">
			<div>
				<img src={AuctionEndImage} />
			</div>
			<h1 className="text-6xl font-bold text-admin-main m-0">
				{__('Product updated!', 'goodbids')}
			</h1>
			<div className="max-w-xl">
				<p className="text-admin-content text-center">
					{__(
						'Your product has been updated and your auction is ready to go! Continue customizing your auction page to make it your own.',
						'goodbids',
					)}
				</p>
			</div>

			<div className="w-fit">
				<ButtonLink
					href={`${gbAuctionWizard.adminURL}/wp-admin/post.php?post=${auctionId}&action=edit`}
					autoFocus
				>
					{__('Return to Auction page', 'goodbids')}
				</ButtonLink>
			</div>
		</div>
	);
}
