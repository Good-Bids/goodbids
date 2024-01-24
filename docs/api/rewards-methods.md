# GoodBids API: Rewards

## Core Methods

`goodbids()->auctions->rewards->get_auction_id( int $reward_product_id )`  
Returns the Auction ID for the given Reward Product ID.

`goodbids()->auctions->rewards->get_category_id()`  
Returns the Rewards Category ID.

`goodbids()->auctions->rewards->get_product_id( int $auction_id )`  
Returns the Reward Product ID for the given Auction ID.

`goodbids()->auctions->rewards->get_product( int $auction_id )`  
Returns the Reward Product for the given Auction ID.

`goodbids()->auctions->rewards->get_claim_reward_url( int $auction_id )`  
Returns the URL where the winner can claim the Reward product.

`goodbids()->auctions->rewards->is_redeemed( int $auction_id )`  
Checks if the Reward product for the given Auction ID has been redeemed.
