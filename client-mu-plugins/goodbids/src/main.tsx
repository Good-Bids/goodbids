import React from 'react';
import ReactDOM from 'react-dom/client';
import './assets/css/main.css';
import { Driver } from './components/driver';

ReactDOM.createRoot(document.getElementById('bidding-block')!).render(
	<React.StrictMode>
		<Driver />
	</React.StrictMode>,
);
