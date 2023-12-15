## Action Hooks

### goodbids_init_site

Action performed when a new site is created. `switch_to_blog()` has already been called to change to the new site.

```php
add_action(
	'goodbids_init_site',
	function( int $site_id ) : void {
		update_option( 'my_option_name', 'my_value' );
	}
);
```
