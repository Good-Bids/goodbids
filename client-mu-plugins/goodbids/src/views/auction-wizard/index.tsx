import { render } from '@wordpress/element';
import { CreateWizard } from './create-wizard';
import { Providers } from './providers';

function renderReact() {
	const root = document.getElementById(gbAuctionWizard.appID);

	if (root) {
		render(
			<Providers>
				<CreateWizard />
			</Providers>,
			root,
		);
	}
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', renderReact);
} else {
	renderReact();
}
