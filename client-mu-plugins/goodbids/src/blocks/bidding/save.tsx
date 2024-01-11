/**
 * WordPress Dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Internal Dependencies
 */
import * as React from 'react';

const Save = ({ attributes }) => {
	const {auctionId} = attributes;
	return (
		<div
			id="goodbids-bidding"
			data-auction-id={auctionId}
			{...useBlockProps.save()}
		></div>
	);
};

export default Save;
