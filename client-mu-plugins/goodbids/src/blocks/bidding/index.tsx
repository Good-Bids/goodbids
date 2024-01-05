/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import json from './block.json';
import Edit from './edit';
import Save from './save';

const { name } = json;

// Register the block
registerBlockType(name, {
	...json,
	edit: Edit,
	save: Save,
});
