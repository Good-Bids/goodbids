/**
 * WordPress Dependencies
 */
import { useSelect } from '@wordpress/data';
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import * as React from 'react';
import { ClockIcon } from './components/clock-icon';
import { WaveIcon } from './components/wave-icon';

const Edit = ({ setAttributes }) => {
	const auctionId = useSelect((select) => {
		// @ts-expect-error Seems like a type error in the @wordpress/data package
		return select('core/editor').getCurrentPostId();
	}, []);

	setAttributes({
		auctionId,
	});

	return (
		<div {...useBlockProps()}>
			<div className="w-full text-lg flex flex-col gap-4">
				<div className="grid grid-cols-3 gap-5 my-4">
					<div className="flex flex-col text-center">
						<p className="m-0 font-thin uppercase has-x-small-font-size">
							BIDS
						</p>
						<p className="m-1 font-extrabold">{0}</p>
					</div>
					<div className="flex flex-col text-center">
						<p className="m-0 font-thin uppercase has-x-small-font-size">
							RAISED
						</p>
						<p className="m-1 font-extrabold">$0</p>
					</div>
					<div className="flex flex-col text-center">
						<p className="m-0 font-thin uppercase has-x-small-font-size">
							LAST BID
						</p>
						<p className="m-1 font-extrabold">$0</p>
					</div>
				</div>
				<div className="flex items-center gap-3 px-4">
					<ClockIcon />

					<span className="text-lg">
						<b>Auction has closed.</b>
					</span>
				</div>
				<div className="bg-base rounded text-white py-2 w-full block text-center no-underline text-lg">
					GOODBID $0 Now
				</div>
				<div className="bg-base rounded text-white py-2 w-full block text-center no-underline text-lg">
					Place free bid
				</div>
				<div className="flex flex-col items-center gap-2">
					<p className="m-0 text-center">
						Watch this auction to be notified when bidding starts.
					</p>
					<p className="m-0 text-center">
						Every GOODBID is a donation.
					</p>
				</div>
				<div className="flex items-start gap-4 bg-gray-100 rounded p-4">
					<WaveIcon />
					<div className="flex flex-col gap-3">
						<p className="m-0">
							<b>Earn free bids:</b>
						</p>
						<p className="m-0">
							Place one of the first five <b>paid bids</b> in this
							auction or{' '}
							<div className="font-bold underline">
								share GOODBIDS with a friend
							</div>{' '}
							to <b>earn a free bid</b>!
						</p>
					</div>
				</div>
			</div>
		</div>
	);
};

export default Edit;
