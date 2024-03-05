import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';

export function Invoices() {
	return (
		<MultiStepHeading
			title={__('Invoices', 'goodbids')}
			content={
				<>
					{__('Visit the', 'goodbids')}{' '}
					<i>{__('Invoices', 'goodbids')}</i>{' '}
					{__(
						'tab to access Auction and Tax invoices payable to GOODBIDS. Once an Invoice has been generated, you can click “Pay Now” to pay the invoice. Invoice emails will be sent to your Finance Contact, or the Primary Contact on your Nonprofit Site if a Finance Contact was not included.',
						'goodbids',
					)}
				</>
			}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.invoicesURL}
				>
					{__('See Invoices', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
