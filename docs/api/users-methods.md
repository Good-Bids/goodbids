# GoodBids API: Users

## Core Methods

`goodbids()->users->get_free_bids( int $user_id )`  
Returns the array of Free Bids for the given User ID.

`goodbids()->users->get_available_free_bid_count( int $user_id )`  
Returns the total available Free Bids for the given User ID.

`goodbids()->users->award_free_bid( int $user_id, int $auction_id, string $description )`  
Awards a user with a Free Bid.

`goodbids()->users->get_emails( int $user_id )`  
Returns an array of emails associated with teh given User ID. If no User ID is provided, the current user will be used.
