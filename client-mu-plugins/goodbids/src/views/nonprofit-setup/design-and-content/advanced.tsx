import { __ } from '@wordpress/i18n';
import { MultiStepHeading } from '../components/multi-step-heading';
import { MultiStepExpansion } from '../components/multi-step-expansion';
import { ButtonLink } from '../../../components/button-link';

export function Advanced() {
	return (
		<MultiStepHeading
			title={__('Advanced', 'goodbids')}
			content={__(
				'Advanced WordPress users can take advantage of the many opportunities to further personalize your GOODBIDS Nonprofit Site.',
				'goodbids',
			)}
		>
			<MultiStepExpansion
				items={[
					{
						title: __('Add and Edit Custom Pages', 'goodbids'),
						content: (
							<>
								{__(
									'You can create additional site pages in the',
									'goodbids',
								)}{' '}
								<i>{__('Pages')}</i>{' '}
								{__(
									'section to highlight your cause. Click Add New Page to get started, and choose from any of the available Patterns and Page Blocks.',
									'goodbids',
								)}
							</>
						),
						component: (
							<div className="w-full max-w-60 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={gbNonprofitSetup.pagesURL}
								>
									{__('Go to Pages', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
					{
						title: __('Save Reusable Patterns', 'goodbids'),
						content: __(
							'Manage what patterns are available when editing content and auction pages on your site. ',
							'goodbids',
						),
						component: (
							<div className="w-full max-w-60 py-3">
								<ButtonLink
									target="_blank"
									variant="solid"
									href={gbNonprofitSetup.patternsURL}
								>
									{__('Go to Patterns', 'goodbids')}
								</ButtonLink>
							</div>
						),
					},
				]}
			/>
		</MultiStepHeading>
	);
}
