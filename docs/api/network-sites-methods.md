# GoodBids API: Network Sites

## Core Methods

`goodbids()->sites->loop( callable $callback, array $site_args )`  
Accepts a callback that will loop through all network sites and return an array of the returned data.

`goodbids()->sites->swap( callable $callback, int $site_id )`  
Accepts a callback that will return the returned value of the callback after swapping to the specified site ID.

`goodbids()->sites->get_all_auctions( array $query_args )`  
Returns an array of all Auctions from all sites.

`goodbids()->sites->get_featured_auctions( array $query_args )`  
Returns an array of the top 3 featured auctions from all sites.

`goodbids()->sites->get_np_data( string $site_id, string $field_id )`  
Returns the custom Nonprofit data for the given network site. If `$field_id` is provided, only that field will be returned, otherwise all fields will be returned.

`goodbids()->sites->get_np_fields( string $context )`  
Returns the array of custom fields, based on the given context (create, edit, or both).

`goodbids()->sites->get_privacy_policy_link()`  
Returns a link for the network privacy policy link html as a string

`goodbids()->sites->get_terms_conditions_link()`
Returns a link for the network terms conditions link html as a string

`goodbids()->sites->get_user_bid_orders( int $user_id, array $status )`
Returns an array of all bid orders for ALL sites for the given user ID and status.

`goodbids()->sites->get_user_total_bids( int $user_id )`
Returns a count of total bids for the given user ID for all sites.

`goodbids()->sites->get_user_total_donated( int $user_id )`
Returns the total amount donated by the given user ID for all sites.

`goodbids()->sites->get_user_nonprofits_supported( int $user_id )`
Returns the count of nonprofits supported by the given user ID for all sites.

`goodbids()->sites->get_user_participating_auctions( int $user_id )`
Returns an array of all auctions the given user ID has participated in for all sites.

`goodbids()->sites->get_user_auctions_won( int $user_id )`
Returns an array of all auctions the given user ID has won for all sites.
