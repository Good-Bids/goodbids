/**
 * WordPress Dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Internal Dependencies
 */
import * as React from 'react';

const Save = ({ attributes }) => {
	return (
		<div
			id="goodbids-bidding"
			data-block-attributes={JSON.stringify(attributes)}
			{...useBlockProps.save()}
		></div>
	);
};

export default Save;
