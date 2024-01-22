# GoodBids API: Auction

## Core Methods

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

`goodbids()->auctions->get_place_bid_url( int $auction_id )`  
Returns the URL to place a bid on the Auction. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_reward_product_id( int $auction_id )`  
Returns the Auction's Reward Product ID. If `$auction_id` is not provided, the current post ID will be used.
 
`goodbids()->auctions->get_reward_product( int $auction_id )`  
Returns the Auction's Reward Product object. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->get_auction_id_from_reward_product_id( int $reward_id )`  
Returns the associated Auction's ID for the given Reward Product ID.

`goodbids()->auctions->get_claim_reward_url( int $auction_id )`  
Returns the URL where the winner can claim the Reward product.

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

`goodbids()->auctions->get_bid_order_ids( int $auction_id, int $limit, int $user_id )`  
Returns an array of Bid Order IDs for the given Auction ID. `$limit` can be specified to return a set number of orders. `$user_id` can be specified to return orders for a specific user.

`goodbids()->auctions->get_bid_orders( int $auction_id, int $limit, int $user_id )`  
Returns an array of Bid Order Objects for the given Auction ID. `$limit` can be specified to return a set number of orders. `$user_id` can be specified to return orders for a specific user.

`goodbids()->auctions->get_free_bid_order_ids( int $auction_id, int $limit, int $user_id )`  
Returns an array of Bid Order IDs for the given Auction ID. `$limit` can be specified to return a set number of orders. `$user_id` can be specified to return free bid orders for a specific user.

`goodbids()->auctions->get_free_bid_orders( int $auction_id, int $limit, int $user_id )`  
Returns an array of Bid Order Objects for the given Auction ID. `$limit` can be specified to return a set number of orders. `$user_id` can be specified to return free bid orders for a specific user.

`goodbids()->auctions->are_free_bids_allowed( int $auction_id )`  
Checks if Free Bids are allowed for the given Auction ID.

`goodbids()->auctions->get_free_bids_available( int $auction_id )`  
Returns the number of available free bids for the given Auction ID.

`goodbids()->auctions->update_free_bids( int $auction_id, int $free_bids )`  
Updates the number of available free bids for the given Auction ID.

`goodbids()->auctions->maybe_award_free_bid( int $auction_id, int $user_id, string $description )`  
Awards a Free Bid to the given User if it is eligible and available.

`goodbids()->auctions->get_last_bid( int $auction_id )`  
Returns the Bid Order object of the last bid for the given Auction ID.

`goodbids()->auctions->get_last_bidder( int $auction_id )`  
Returns the User object that placed the last bid.

`goodbids()->auctions->get_winning_bidder( int $auction_id )`  
Returns the User object of the winning bidder. Returns null if Auction has not ended.

`goodbids()->auctions->is_current_user_winner( int $auction_id )`  
Checks if the current user is the winner of the Auction.

`goodbids()->auctions->get_bid_count( int $auction_id )`  
Get the number of bids for the given Auction ID.

`goodbids()->auctions->get_user_bid_count( int $auction_id, int $user_id )`  
Get the number of bids by the given User for the given Auction ID.

`goodbids()->auctions->get_user_total_donated( int $auction_id, int $user_id )`  
Get the total amount donated by the given User for the given Auction ID.

`goodbids()->auctions->get_status( int $auction_id )`  
Returns the status of the Auction. Possible values are: "Upcoming", "Live", and "Closed". If the Auction has not yet been published, it will return "Draft".

`goodbids()->auctions->get_all_site_auctions()`  
Returns an array of all active auctions for all sites.
