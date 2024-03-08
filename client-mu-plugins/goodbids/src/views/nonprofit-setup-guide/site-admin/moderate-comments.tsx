import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { ButtonLink } from '../../../components/button-link';
import { Footnote } from '../components/footnote';

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
						'tab. You will be emailed when a comment requires moderation.',
						'goodbids',
					)}
				</>
			}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.commentsURL}
				>
					{__('See Comments', 'goodbids')}
				</ButtonLink>
			</div>

			<Footnote>
				{__(
					'You can manage your comment moderation settings on the',
					'goodbids',
				)}{' '}
				<i>{__('Settings > Discussion', 'goodbids')}</i>{' '}
				{__('page.', 'goodbids')}
			</Footnote>
		</MultiStepHeading>
	);
}
