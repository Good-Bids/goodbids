/**
 * WordPress Dependencies
 */
import React from 'react';

const Save = ({ attributes }) => {
	return (
		<div
			id="goodbids-bidding"
			data-gutenberg-attributes={JSON.stringify(attributes)}
		></div>
	);
};

export default Save;
