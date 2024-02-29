import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { getBaseAdminUrl } from '../../../utils/get-base-url';
import { MultiStepHeading } from '../components/multi-step-heading';

const THEME_URL = '/wp-admin/themes.php';

export function UpdateStyle() {
	const baseUrl = getBaseAdminUrl();

	return (
		<MultiStepHeading
			title={__('Update Style', 'goodbids')}
			content={__(
				"'Choose a Theme Style that best fits your Nonprofit's look and feel, then click the pencil icon to make it your onw. You can change the colors, typography, and sitewide layout. Your style preferences can be modified in the Appearance > Editor section.",
				'goodbids',
			)}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={`${baseUrl}${THEME_URL}`}
				>
					{__('Choose a Style', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
