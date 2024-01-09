# GoodBids API: ACF Block

## Core Methods

`goodbids()->acf->blocks()->get_all_blocks()`  
Get all custom registered blocks.

`goodbids()->acf->blocks()->get_block( string $block_name )`  
Get block array by block name.

`goodbids()->acf->blocks()->block_attr()`  
_Use the global `block_attr()` helper function instead._ This will render the block attributes for the current block.

`goodbids()->acf->blocks()->get_block_location( string $block_name, string $return )`  
Get the location of a block. Return values can be: "directory" (Default) or "json" (Returns the path to block.json. _Can also be found in the `path` key of the block array._

`goodbids()->acf->blocks()->get_block_locations()`  
Get all directories where blocks can be found.
