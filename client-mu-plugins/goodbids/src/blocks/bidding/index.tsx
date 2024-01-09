/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import Edit from './edit';
import Save from './save';

const settings = {
	edit: Edit,
	save: Save,
};

// Register the block
// @ts-expect-error Type error in the @wordpress/blocks package
registerBlockType(metadata, settings);
