import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { getBaseAdminUrl } from '../../../utils/get-base-url';
import { MultiStepHeading } from '../components/multi-step-heading';

// TODO: Update this URL
const HOMEPAGE_URL = '#';

export function ReviewHomepage() {
	const baseUrl = getBaseAdminUrl();

	return (
		<MultiStepHeading
			title={__('Review Homepage', 'goodbids')}
			content={__(
				'Review your homepage and make sure everything looks good. You can revisit this post-launch on the Appearance > Customize page in the WordPress Admin.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={`${baseUrl}${HOMEPAGE_URL}`}
				>
					{__('Review Homepage', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
