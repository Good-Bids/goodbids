import * as React from 'react';
import { render } from '@wordpress/element';
import { Driver } from './components/Driver';

window.onload = () => {
	const container = document.getElementById('goodbids-bidding');

	if (container) {
		render(<Driver />, document.getElementById('goodbids-bidding'));
	}
};
