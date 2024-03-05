import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';

export function ModerateComments() {
	return (
		<MultiStepHeading
			title={__('Moderate Comments', 'goodbids')}
			content={
				<>
					{__(
						'If you choose to enable comments on your Auction pages, you can view and moderate them from the',
						'goodbids',
					)}{' '}
					<i>{__('Comments')}</i>{' '}
					{__(
						'tab. You will also be emailed when a comment requires moderation. You can manage your comment moderation settings in the',
						'goodbids',
					)}{' '}
					<i>{__('Settings > Discussion')}</i>{' '}
					{__('tab.', 'goodbids')}
				</>
			}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetup.commentsURL}
				>
					{__('See Comments', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
