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
Returns the Auction post type slug.

`goodbids()->auctions->get_auction_id()`  
Returns the current Auction ID (if valid).

`goodbids()->auctions->get_setting( string $setting, int $auction_id )`  
Returns a setting value for an auction. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->has_bid_product( int $auction_id )`  
Checks if the Auction has a Bid Product. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_bid_product_id( int $auction_id )`  
Returns the Auction's Bid Product ID. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_bid_product( int $auction_id )`  
Returns the Auction's Bid Product object. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->set_bid_product_id( int $auction_id, int $bid_product_id )`  
Associates the Bid Product with the Auction and the Auction with the Bid Product.

`goodbids()->auctions->get_reward_product_id( int $auction_id )`  
Returns the Auction's Reward Product ID. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_reward_product( int $auction_id )`  
Returns the Auction's Reward Product object. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_product_type( int $product_id )`  
Returns the type of the given product ID. Possible types are: "bids", "rewards", or null.

`goodbids()->auctions->get_estimated_value( int $auction_id )`  
Returns the Auction Reward's Estimated Value. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_start_date_time( int $auction_id, string $format )`  
Returns the Auction's Start Date/Time in MySQL format. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_end_date_time( int $auction_id, string $format )`  
Returns the Auction's End Date/Time in MySQL format. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->has_started( int $auction_id )`  
Checks if the Auction has started. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->has_ended( int $auction_id )`  
Checks if the Auction has ended. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_extensions( int $auction_id )`  
Returns the number of times the Auction has been extended. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->is_extension_window( int $auction_id )`  
Checks if the Auction is currently inside the extension window. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_bid_extension( int $auction_id )`  
Returns the Auction's Bid Extension value (in seconds). If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_bid_increment( int $auction_id )`  
Returns the Auction's Bid Increment value. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_starting_bid( int $auction_id )`  
Returns the Auction's Starting Bid value. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->calculate_starting_bid( int $auction_id )`  
Returns the Auction's Calculated Starting Bid value, which will use the Bid Increment if the Starting Bid is not set. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_goal( int $auction_id )`  
Returns the Auction's Goal value. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_expected_high_bid( int $auction_id )`  
Returns the Auction's Expected High Bid value. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_guid( int $auction_id )`  
Returns the Auction's Unique GUID. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_bid_order_ids( int $auction_id, int $limit )`  
Returns an array of Bid Order IDs for the given Auction ID. `$limit` can be specified to return a set number of orders.

`goodbids()->auctions->get_bid_orders( int $auction_id, int $limit )`  
Returns an array of Bid Orders for the given Auction ID. `$limit` can be specified to return a set number of orders.

`goodbids()->auctions->get_bid_order( int $auction_id, int $limit )`  
Returns an array of Bid Order objects for the given Auction ID. `$limit` can be specified to return a set number of orders.

`goodbids()->auctions->get_last_bid( int $auction_id )`  
Returns the Bid Order object of the last bid for the given Auction ID.

`goodbids()->auctions->get_last_bidder( int $auction_id )`  
Returns the User object that placed the last bid.

`goodbids()->auctions->get_bid_count( int $auction_id )`  
Get the number of bids for the given Auction ID.

`goodbids()->auctions->get_status( int $auction_id )`  
Returns the status of the Auction. Possible values are: "Upcoming", "Live", and "Closed". If the Auction has not yet been published, it will return "Draft".

### Bids Functions

`goodbids()->auctions->bids->get_auction_id( int $bid_product_id )`  
Returns the Auction ID for the given Bid Product ID.

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

### WooCommerce Functions

`goodbids()->woocommerce->get_order_auction_info( int $order_id )`  
Returns an array of information for the given Order ID, including the Auction ID (`auction_id`) and Order Type (`order_type`).

`goodbids()->woocommerce->get_order_auction_id( int $order_id )`  
Returns the Auction ID for the given Order ID. If `$order_id` is not provided, the current order ID will be used.

`goodbids()->woocommerce->get_order_type( int $order_id )`  
Returns the type of the given order ID. Possible types are: "bids", "rewards", or null.

`goodbids()->woocommerce->is_bid_order( int $order_id )`  
Checks if the given order ID is a Bid Order.

`goodbids()->woocommerce->is_reward_order( int $order_id )`  
Checks if the given order ID is a Reward Order.

### Auctioneer Functions

`goodbids()->auctioneer->request( string $endpoint, array $data, string $method )`  
Makes a request to the Auctioneer API.

`goodbids()->auctioneer->get_last_response()`  
Returns the last response from the Auctioneer API.

`goodbids()->auctioneer->is_invalid_response( mixed $response )`  
Checks if the API response is valid.

`goodbids()->auctioneer->get_response_json( array $response )`  
Returns the API response body data as an associative array.

`goodbids()->auctioneer->get_response_message( array $response )`  
Retrieves the formatted response message from the API response body.

`goodbids()->auctioneer->get_response_message_raw( array $response )`  
Retrieves the raw response message from the API response body.

### Auctioneer: Auction (Endpoint) Functions

`goodbids()->auctioneer->auctions->start( int $auction_id )`
Signals to the Auctioneer API that the Auction has started.

`goodbids()->auctioneer->auctions->update( int $auction_id, string $context )`
Sends an update to the Auctioneer API. The context provided should identify the purpose of the update and will be pre-pended with `update:` before requesting the payload.

`goodbids()->auctioneer->auctions->end( int $auction_id )`
Signals to the Auctioneer API that the Auction has ended.
