import { Tips } from '~/components/tips';
import { Form } from './form';
import { __ } from '@wordpress/i18n';

export function AuctionForm() {
	return (
		<div className="flex gap-4 justify-between">
			<Form />

			<Tips>
				<p>
					{__(
						'Consider setting your Auction Start to align with marketing efforts. If you want to start the Auction with a particular starting bid, ensure that you set that here, otherwise the first bid will be your bid increment. To keep an Auction going when late bids are received, consider adding a bid extension window.',
						'goodbids',
					)}
				</p>
			</Tips>
		</div>
	);
}
