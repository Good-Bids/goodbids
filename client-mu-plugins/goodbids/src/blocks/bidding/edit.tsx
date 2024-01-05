/**
 * WordPress Dependencies
 */
import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { Driver } from './components/driver';

const Edit = ({ setAttributes }) => {
	const auctionId = useSelect((select) => {
		// @ts-expect-error Seems like a type error in the @wordpress/data package
		return select('core/editor').getCurrentPostId();
	}, []);

	const blockProps = useBlockProps({
		className: 'p-8',
	});

	setAttributes({ auctionId });

	return (
		<div {...blockProps}>
			<Driver />
		</div>
	);
};

export default Edit;
