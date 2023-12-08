## Filter Hooks

### goodbids_nonprofit_custom_fields

Add custom fields to the Nonprofit meta data during new site creation.

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
