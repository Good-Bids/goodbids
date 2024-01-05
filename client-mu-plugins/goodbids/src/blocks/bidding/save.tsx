/**
 * WordPress Dependencies
 */
import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';

const Save = ({ attributes }) => {
	const blockProps = useBlockProps.save();

	return (
		<div {...blockProps}>
			<span>Hello World!</span>
			{attributes.auctionId && (
				<ul>
					<li>Auction ID: {attributes.auctionId}</li>
				</ul>
			)}
		</div>
	);
};

export default Save;
