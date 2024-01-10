/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Block metadata
 */
// @ts-expect-error No issues loading json file.
import metadata from './block.json';

/**
 * Internal dependencies
 */
import Edit from './edit';
import Save from './save';

/**
 * Block registration
 */
const settings = {
	edit: Edit,
	save: Save,
};

// Register the block
registerBlockType(metadata, settings);
