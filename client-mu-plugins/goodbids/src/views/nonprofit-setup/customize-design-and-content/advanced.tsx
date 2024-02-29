import { __ } from '@wordpress/i18n';
import { ButtonLink } from '../../../components/button-link';
import { getBaseAdminUrl } from '../../../utils/get-base-url';
import { MultiStepHeading } from '../components/multi-step-heading';

// TODO: Update this URL
const EXTENSIONS_URL = '#';

export function Advanced() {
	const baseUrl = getBaseAdminUrl();

	return (
		<MultiStepHeading
			title={__('Advanced', 'goodbids')}
			content={__('Do some advanced stuff here.', 'goodbids')}
		>
			<div className="w-full max-w-60">
				<ButtonLink
					target="_blank"
					variant="solid"
					href={`${baseUrl}${EXTENSIONS_URL}`}
				>
					{__('Advanced', 'goodbids')}
				</ButtonLink>
			</div>
		</MultiStepHeading>
	);
}
