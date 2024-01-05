/**
 * WordPress Dependencies
 */
import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import { Driver } from './components/driver';

const Save = ({ attributes }) => {
	const blockProps = useBlockProps.save();
	console.log(attributes);

	return (
		<div {...blockProps}>
			<Driver />
		</div>
	);
};

export default Save;
