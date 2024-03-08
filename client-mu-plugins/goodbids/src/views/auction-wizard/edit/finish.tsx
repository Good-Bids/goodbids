import { ButtonLink } from '~/components/button-link';
import { __ } from '@wordpress/i18n';
import { H1, P } from '~/components/typography';
import { CoinManImage } from '~/components/images/coin-man';

type FinishStepProps = {
	auctionId: number;
};

export function FinishStep({ auctionId }: FinishStepProps) {
	return (
		<div className="flex flex-col items-center gap-8 p-10">
			<CoinManImage className="aspect-auto h-50 py-10" />

			<div className="flex flex-col gap-3">
				<H1>{__('Product updated!', 'goodbids')}</H1>
				<P>
					{__(
						'Your product has been updated! Continue customizing your auction page to make it your own.',
						'goodbids',
					)}
				</P>
			</div>

			<ButtonLink
				autoFocus
				href={`${gbAuctionWizard.adminURL}/post.php?post=${auctionId}&action=edit`}
				variant="solid"
			>
				{__('Return to auction page', 'goodbids')}
			</ButtonLink>
		</div>
	);
}
