/**
 * WordPress Dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Internal Dependencies
 */

type SaveProps = {
	attributes: {
		auctionId: number;
	};
};

const Save = ({ attributes }: SaveProps) => {
	const { auctionId } = attributes;
	return (
		<div
			id="goodbids-bidding"
			data-auction-id={auctionId}
			{...useBlockProps.save()}
		></div>
	);
};

export default Save;
