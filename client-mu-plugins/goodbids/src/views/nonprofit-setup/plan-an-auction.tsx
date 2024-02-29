import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../components/button-link';
import { Card } from './components/card';
import { getBaseAdminUrl } from '../../utils/get-base-url';
import { CardHeading } from './components/card-heading';

const AUCTION_WIZARD_URL =
	'/wp-admin/edit.php?post_type=gb-auction&page=gb-auction-wizard';

export function PlanAnAuction() {
	const baseUrl = getBaseAdminUrl();

	return (
		<Card>
			<CardHeading
				title={__('Plan an Auction', 'goodbids')}
				content={__(
					"Plan and create your first auction! You'll be able to customize the auction details, set the auction date, and more.",
					'goodbids',
				)}
			>
				<div className="w-full max-w-40">
					<ButtonLink
						variant="solid"
						href={`${baseUrl}${AUCTION_WIZARD_URL}`}
					>
						{__('Get Started', 'goodbids')}
					</ButtonLink>
				</div>
			</CardHeading>
		</Card>
	);
}
