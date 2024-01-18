import { render } from '@wordpress/element';
import { Driver } from './components/Driver';

function renderReact() {
	const root = document.getElementById('goodbids-bidding');

	if (root) {
		render(<Driver />, root);
	}
}

if (document.readyState === 'loading') {
	// Loading hasn't finished yet
	document.addEventListener('DOMContentLoaded', renderReact);
} else {
	// `DOMContentLoaded` has already fired
	renderReact();
}
