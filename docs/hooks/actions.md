## Action Hooks

### goodbids_init_site

Action performed when a new site is created. `switch_to_blog()` has already been called to change to the new site.

```php
add_action(
	'goodbids_init_site',
	function ( int $site_id ): void {
		update_option( 'my_option_name', 'my_value' );
	}
);
```

### goodbids_order_payment_complete

Action performed after an Auction-related order has a successful payment.

```php
add_action(
	'goodbids_order_payment_complete',
	function ( int $order_id, int $auction_id ): void {
		$bid_count = get_post_meta( $auction_id, '_bid_count', true );
		update_post_meta( $auction_id, '_bid_count', $bid_count + 1 );
	}
);
```
### goodbids_auction_start_event

Cron event, fired every minute.

> **Be EXTREMELY CAUTIOUS using this hook.**

```php
add_action(
	'goodbids_auction_start_event',
	function (): void {
		// Are you sure you want to use this hook?
	}
);
```
