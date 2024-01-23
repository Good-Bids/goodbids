# GoodBids API: Bids

## Core Methods

`goodbids()->auctions->bids->get_auction_id( int $bid_product_id )`  
Returns the Auction ID for the given Bid Product ID.

`goodbids()->auctions->bids->get_category_id( int $bid_product_id )`  
Returns the Bids category ID.

`goodbids()->auctions->bids->get_product_id( int $auction_id )`  
Returns the Auction's Bid Product ID. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->bids->get_product( int $auction_id )`  
Returns the Auction's Bid Product object. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->bids->get_variation_id( int $auction_id )`  
Returns the Auction's Bid Variation ID. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->bids->get_variation( int $auction_id )`  
Returns the Auction's Bid Variation object. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->bids->get_place_bid_url( int $auction_id )`  
Returns the URL to place a bid on the Auction. If `$auction_id` is not provided, the current post ID will be used.

`goodbids()->auctions->bids->create_new_bid_variation( int $bid_product_id, float $bid_price, int $auction_id )`  
Generates a new Bid Variation for the Auction.

`goodbids()->auctions->bids->increase_bid( int $auction_id )`  
Increases the bid amount for the given Auction ID.
