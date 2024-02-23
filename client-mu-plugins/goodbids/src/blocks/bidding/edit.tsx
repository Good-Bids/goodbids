/**
 * WordPress Dependencies
 */
import { useSelect } from '@wordpress/data';
import { useBlockProps } from '@wordpress/block-editor';
import { BlockEditorView } from './components/block-editor-view';

/**
 * Internal dependencies
 */

type EditProps = {
	setAttributes: (attributes: { auctionId: number }) => void;
};

const Edit = ({ setAttributes }: EditProps) => {
	const auctionId = useSelect((select) => {
		// @ts-expect-error Seems like a type error in the @wordpress/data package
		return select('core/editor').getCurrentPostId();
	}, []);

	setAttributes({
		auctionId,
	});

	return (
		<div {...useBlockProps()}>
			<BlockEditorView />
		</div>
	);
};

export default Edit;
