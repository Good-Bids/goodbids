import AuctionStartImage from '../../../../assets/images/auction-start.png';
import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { H1, P } from '../../../components/typography';

export function CreateStoreStep() {
	return (
		<div className="w-full flex flex-col items-center py-10 gap-4">
			<img src={AuctionStartImage} />

			<H1>{__('Welcome to GOODBIDS!', 'goodbids')}</H1>

			<div className="max-w-xl pb-8">
				<P>
					{__(
						'Thanks for joining the GOODBIDS network. We’re so happy to have you! To get started with your new Nonprofit site, we need you to complete just a few required onboarding steps. First, click the button below to automate the setup for your WooCommerce store. It’s that easy!',
						'goodbids',
					)}
				</P>
			</div>

			<ButtonLink href={gbNonprofitOnboarding.createStoreUrl}>
				{__('Create my Store', 'goodbids')}
			</ButtonLink>
		</div>
	);
}
