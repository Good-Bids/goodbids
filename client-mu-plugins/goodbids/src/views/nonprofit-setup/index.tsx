import { render } from '@wordpress/element';

function renderReact() {
	const root = document.getElementById(gbNonprofitSetup.appID);

	if (root) {
		render(<div>hi</div>, root);
	}
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', renderReact);
} else {
	renderReact();
}
