## Filter Hooks

### goodbids_nonprofit_custom_fields

Modify Nonprofit custom fields.

```php
add_filter(
	'goodbids_nonprofit_custom_fields',
	function( array $fields ) : array {
		$fields['key'] = [
			'label'       => __( 'Custom Field', 'goodbids' ),
			'type'        => 'text',
			'required'    => false,
			'default'     => '',
			'placeholder' => '',
		];
		
		return $fields;
	}
);
```
### goodbids_bid_product_create

Modify the new bid product associated with a product.

```php
add_filter(
	'goodbids_bid_product',
	function( WC_Product_Simple $bid_product, int $post_id ) : WC_Product_Simple {
		$bid_product->set_regular_price( 2 );
		return $bid_product;
	}
);
```
