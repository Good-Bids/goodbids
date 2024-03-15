import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { MultiStepHeading } from '../components/multi-step-heading';
import { Footnote } from '../components/footnote';

export function SiteAppearance() {
	return (
		<MultiStepHeading
			title={__('Site Appearance', 'goodbids')}
			content={__(
				'Choose a Theme Style that best fits your Nonprofit’s look and feel, then click the pencil icon to make it your own. You can change the colors, typography, and sitewide layout. Adjust the Palette in the Colors tab to apply your brand colors across the site.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={gbNonprofitSetupGuide.styleURL}
				>
					{__('Choose a Style', 'goodbids')}
				</ButtonLink>
			</div>

			<Footnote>
				{__(
					'Your style preferences can be modified in the',
					'goodbids',
				)}{' '}
				<i>{__('Appearance > Editor', 'goodbids')}</i>{' '}
				{__('tab.', 'goodbids')}
			</Footnote>
		</MultiStepHeading>
	);
}
