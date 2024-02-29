import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { getBaseAdminUrl } from '../../../utils/get-base-url';
import { MultiStepHeading } from '../components/multi-step-heading';

const SITE_SETTINGS_URL = '/wp-admin/options-general.php';

export function FinalizeDetails() {
	const baseUrl = getBaseAdminUrl();

	return (
		<MultiStepHeading
			title={__('Finalize Details', 'goodbids')}
			content={__(
				'Click the button below to review and update your site name, tagline, and timezone. You can revisit these post-launch on the Settings > General page in the WordPress Admin.',
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={`${baseUrl}${SITE_SETTINGS_URL}`}
				>
					{__('Update Site Settings', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
