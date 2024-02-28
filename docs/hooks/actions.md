## Action Hooks

### goodbids_nonprofit_verified

Action performed when a site has been verified.

```php
add_action(
	'goodbids_nonprofit_verified',
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
	},
	10,
	2
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

### goodbids_auction_start

Fired when an auction starts.

```php
add_action(
	'goodbids_auction_start',
	function ( int $auction_id ): void {
		// Maybe update some post meta?
	}
);
```

### goodbids_auction_close

Fired when an auction ends.

```php
add_action(
	'goodbids_auction_close',
	function ( int $auction_id ): void {
		// Maybe update some post meta?
	}
);
```
