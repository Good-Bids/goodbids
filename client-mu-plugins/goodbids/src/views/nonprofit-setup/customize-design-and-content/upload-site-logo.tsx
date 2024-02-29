import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { getBaseAdminUrl } from '../../../utils/get-base-url';
import { MultiStepHeading } from '../components/multi-step-heading';

// TODO: Update this URL
const LOGO_URL = '#';

export function UploadSiteLogo() {
	const baseUrl = getBaseAdminUrl();

	return (
		<MultiStepHeading
			title={__('Upload Site Logo', 'goodbids')}
			content={__(
				'Upload a logo for your site. You can revisit this post-launch on the Appearance > Customize page in the WordPress Admin.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={`${baseUrl}${LOGO_URL}`}
				>
					{__('Upload Site Logo', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
