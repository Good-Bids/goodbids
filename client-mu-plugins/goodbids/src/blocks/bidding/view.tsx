import { render } from '@wordpress/element';
import { Driver } from './components/Driver';

function renderReact() {
	const root = document.getElementById('goodbids-bidding');
	const rootAuctionId = root?.dataset.auctionId;

	if (root && rootAuctionId) {
		const auctionId = parseInt(rootAuctionId);
		render(<Driver auctionId={auctionId} />, root);
	}
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', renderReact);
} else {
	renderReact();
}
