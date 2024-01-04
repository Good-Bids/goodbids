import React from 'react';
import ReactDOM from 'react-dom/client';
import './assets/css/main.css';

const root = document.getElementById('bidding-block');

const auctionId = root?.getAttribute('data-auction-id');
const initialBids = root?.getAttribute('data-initial-bids');
const initialRaised = root?.getAttribute('data-initial-raised');
const initialLastBid = root?.getAttribute('data-initial-last-bid');
const initialEndTime = root?.getAttribute('initial-end-time');
const initialFreeBids = root?.getAttribute('initial-free-bids');
const initialUserBids = root?.getAttribute('initial-user-bids');
const initialLastBidder = root?.getAttribute('initial-last-bidder');

ReactDOM.createRoot(root!).render(
	<React.StrictMode>
		<div className="p-8">
			<span>Hello World. Here are some default values.</span>
			<ul>
				<li>{auctionId}</li>
				<li>{initialBids}</li>
				<li>{initialRaised}</li>
				<li>{initialLastBid}</li>
				<li>{initialEndTime}</li>
				<li>{initialFreeBids}</li>
				<li>{initialUserBids}</li>
				<li>{initialLastBidder}</li>
			</ul>
		</div>
	</React.StrictMode>,
);
