# GoodBids API: Orders

## Core Methods

`goodbids()->woocommerce->orders->get_auction_info( int $order_id )`  
Returns an array of information for the given Order ID, including the Auction ID (`auction_id`) and Order Type (`order_type`).

`goodbids()->woocommerce->orders->get_auction_id( int $order_id )`  
Returns the Auction ID for the given Order ID. If `$order_id` is not provided, the current order ID will be used.

`goodbids()->woocommerce->orders->get_type( int $order_id )`  
Returns the type of the given order ID. Possible types are: "bids", "rewards", or null.

`goodbids()->woocommerce->orders->is_bid_order( int $order_id )`  
Checks if the given order ID is a Bid Order.

`goodbids()->woocommerce->orders->is_reward_order( int $order_id )`  
Checks if the given order ID is a Reward Order.

`goodbids()->woocommerce->orders->is_free_bid_order( int $order_id )`  
Checks if the given order ID was paid using a Free Bid.
