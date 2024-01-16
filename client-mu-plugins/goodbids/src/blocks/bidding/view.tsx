import * as React from 'react';
import { render } from '@wordpress/element';
import { Driver } from './components/Driver';

function renderReact() {
	render(<Driver />, document.getElementById('goodbids-bidding'));
}

if (document.readyState === 'loading') {
	// Loading hasn't finished yet
	document.addEventListener('DOMContentLoaded', renderReact);
} else {
	// `DOMContentLoaded` has already fired
	renderReact();
}
