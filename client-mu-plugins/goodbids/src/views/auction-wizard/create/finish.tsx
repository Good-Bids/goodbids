import { ButtonLink } from '~/components/button-link';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';
import { CoinManImage } from '~/components/images/coin-man';
import { H1, P } from '~/components/typography';

export function FinishStep() {
	const { auctionId } = useAuctionWizardState();

	return (
		<div className="flex flex-col items-center gap-8 p-10">
			<CoinManImage className="aspect-auto h-60 py-10" />

			<div className="flex flex-col gap-3">
				<H1>{__('Almost there!', 'goodbids')}</H1>
				<P>
					{__(
						'Your auction is set up! You can now edit your auction page to really make it shine! Consider adding additional content to your auction page to get potential bidders interested. Once you have completed your edits, publish your auction!',
						'goodbids',
					)}
				</P>
			</div>

			<ButtonLink
				autoFocus
				href={`${gbAuctionWizard.adminURL}/post.php?post=${auctionId}&action=edit`}
				variant="solid"
			>
				{__('Customize auction page', 'goodbids')}
			</ButtonLink>
		</div>
	);
}
