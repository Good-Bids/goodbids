# GoodBids Plugin

A plugin that powers the GoodBids.org site.

## GoodBids API

This is a quick reference of some of the functions available in the GoodBids API.

Call the API using the `goodbids()` function.

### Core Functions

`goodbids()->get_version()`  
Returns the current version of the GoodBids plugin.

`goodbids()->get_config( string $setting )`  
Returns the config value for the given setting.

`goodbids()->is_plugin_active( string $plugin )`  
Checks if the given plugin (slug) is active. This is not the same as the WordPress `is_plugin_active()` function, all it does is checks if the plugin is in the active_plugins config.

### Network Sites Functions

`goodbids()->sites->get_np_data( string $site_id, string $field_id )`  
Returns the custom Nonprofit data for the given network site. If `$field_id` is provided, only that field will be returned, otherwise all fields will be returned.

`goodbids()->sites->get_np_fields( string $context )`  
Returns the array of custom fields, based on the given context (create, edit, or both).

### Admin Functions

`goodbids()->admin->render_field( string $key, array $field, string $prefix, array $data )`  
Renders an admin field based on the given field array. The `$prefix` and `$data` parameters are optional, but required for some field types.

### Auction Functions

`goodbids()->auctions->get_post_type()`  
Returns the auction post type slug.

`goodbids()->auctions->get_setting( string $setting, int $auction_id )`  
Returns a setting value for an auction. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_prize_product_id( int $auction_id )`  
Returns the Auction's Prize Product ID. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_start_date_time( int $auction_id )`  
Returns the Auction's Start Date/Time in MySQL format. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_bid_increment( int $auction_id )`  
Returns the Auction's Bid Increment value. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_goal( int $auction_id )`  
Returns the Auction's Goal value. If `$auction_id` is not provided, the current post ID will be used.

### ACF Block Functions

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
