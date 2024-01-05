/**
 * WordPress Dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';

const Edit = ({ setAttributes }) => {
	const auctionId = useSelect((select) => {
		return select('core/editor').getCurrentPostId();
	}, []);

	const blockProps = useBlockProps({
		className: 'p-8',
	});

	setAttributes({ auctionId });

	return (
		<div {...blockProps}>
			<span>Hello World!</span>
			{auctionId && (
				<ul>
					<li>Auction ID: {auctionId}</li>
				</ul>
			)}
		</div>
	);
};

export default Edit;
