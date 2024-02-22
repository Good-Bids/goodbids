# GoodBids API: Watchers

## Core Methods

All instances of `$auction_id` and `$user_id` are optional. If not provided, the current user and post are used, respectively.

`goodbids()->watchers->get_post_type()`  
Returns the post type key for Watchers.

`goodbids()->watchers->get_watcher( int $auction_id, int $user_id )`  
Returns the Watcher ID for the given Auction ID and User ID.

`goodbids()->watchers->is_watching( int $auction_id, int $user_id )`  
Checks if the given User ID is watching the given Auction ID.

`goodbids()->watchers->start_watching( int $auction_id, int $user_id )`  
Starts Watching an Auction for the given User ID.

`goodbids()->watchers->stop_watching( int $auction_id, int $user_id )`  
Stops Watching an Auction for the given User ID.

`goodbids()->watchers->toggle_watching( int $auction_id, int $user_id )`  
Starts or Stops Watching an Auction for the given User ID, based on if it's currently being watched.

`goodbids()->watchers->get_watchers_by_user( int $user_id )`  
Returns an array of IDs of Watchers for the given User ID.

`goodbids()->watchers->get_watchers_by_auction( int $auction_id )`  
Returns an array of IDs of Watchers for the given Auction ID.

`goodbids()->watchers->get_watcher_count( int $auction_id )`  
Returns the total number of Watchers for the given Auction ID.

`goodbids()->watchers->get_auction_id( int $auction_id )`  
Return the Auction ID for a given Watcher.
