/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Block metadata
 */
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
// @ts-expect-error @wordpress/blocks just does not type well
registerBlockType(metadata, settings);
