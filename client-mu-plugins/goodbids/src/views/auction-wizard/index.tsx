import { render } from '@wordpress/element';
import { Driver } from './driver';
import { Providers } from './providers';

function renderReact() {
	const root = document.getElementById(gbAuctionWizard.appID);

	if (root) {
		render(
			<Providers>
				<Driver />
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
