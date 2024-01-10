# GoodBids API: Auctioneer

## Core Methods

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

`goodbids()->auctioneer->get_url()`  
Returns the URL to Auctioneer.

## Auction (Endpoint) Methods

`goodbids()->auctioneer->auctions->start( int $auction_id )`
Signals to the Auctioneer API that the Auction has started.

`goodbids()->auctioneer->auctions->update( int $auction_id, string $context )`
Sends an update to the Auctioneer API. The context provided should identify the purpose of the update and will be pre-pended with `update:` before requesting the payload.

`goodbids()->auctioneer->auctions->end( int $auction_id )`
Signals to the Auctioneer API that the Auction has ended.
