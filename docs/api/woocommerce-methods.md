# GoodBids API: WooCommerce

## Core Methods

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

`goodbids()->woocommerce->is_free_bid_order( int $order_id )`  
Checks if the given order ID was paid using a Free Bid.

`goodbids()->woocommerce->get_add_to_cart_item( WC_Product $product )`  
Returns the "add-to-cart" Product if not provided in the `$product` parameter.
