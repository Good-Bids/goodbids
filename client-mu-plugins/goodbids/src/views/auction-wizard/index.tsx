import { render } from '@wordpress/element';
import { Driver } from './driver';

function renderReact() {
	const root = document.getElementById(gbAuctionWizard.appID);

	if (root) {
		render(<Driver />, root);
	}
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', renderReact);
} else {
	renderReact();
}
