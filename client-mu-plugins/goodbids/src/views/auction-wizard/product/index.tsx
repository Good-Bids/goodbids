import { render } from '@wordpress/element';
import { Main } from './main';

function renderReact() {
	const root = document.getElementById('gb-auction-wizard-product');

	if (root) {
		render(<Main />, root);
	}
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', renderReact);
} else {
	renderReact();
}
