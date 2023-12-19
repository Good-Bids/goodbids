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

### goodbids_block_class

Modifies the classes for custom blocks.

```php
add_filter(
	'goodbids_block_class',
	function( array $class, array $block ) : array {
		$class[] = 'my-custom-class';
		return $class;
	}
);
```
### goodbids_block_locations

Adds support for additional custom block directories.

```php
add_filter(
	'goodbids_block_locations',
	function( array $locations ) : array {
		$locations[] = get_stylesheet_directory() . '/blocks';
		return $locations;
	}
);
```
### goodbids_block_patterns

Registers custom block patterns.

```php
add_filter(
	'goodbids_block_patterns',
	function ( array $patterns ): array {
		$theme_patterns = [
			[
				'name'     => 'goodbids-np/my-pattern',
				'path'     => get_stylesheet_directory() . '/patterns/my-pattern.php',
				'title'    => __( 'My Pattern', 'goodbids-nonprofit' ),
				'source'   => 'theme',
				'inserter' => true,
			]
		];

		return array_merge( $patterns, $theme_patterns );
	}
```
