import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';

export function SiteAppearance() {
	return (
		<MultiStepHeading
			title={__('Site Appearance', 'goodbids')}
			content={
				<>
					{__(
						'Choose a Theme Style that best fits your Nonprofit’s look and feel, then click the pencil icon to make it your own. You can change the colors, typography, and sitewide layout. Your style preferences can be modified in the',
						'goodbids',
					)}{' '}
					<i>{__('Appearance > Editor section.', 'goodbids')}</i>
				</>
			}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetup.styleURL}
				>
					{__('Choose a Style', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
