import * as React from 'react';
import { render } from '@wordpress/element';
import { Driver } from './components/Driver';

window.onload = () => {
	render(<Driver />, document.getElementById('goodbids-bidding'));
};
