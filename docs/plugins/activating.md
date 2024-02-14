# Activating Plugins

## Global Plugins

Global plugins to be used on all sites can be activated by adding the plugin slug to the `active-plugins` array in the GoodBids MU Plugin `config.json` file.

If the plugin slug does not match the plugin filename (e.g. `woocommerce/woocommerce.php`), you need to specify the both the slug and plugin filename. (Example: `advanced-custom-fields-pro/acf.php`)
 
```json
{
  "active-plugins": [
	"woocommerce",
	"advanced-custom-fields-pro/acf.php"
  ]
}
```

## Site Specific Plugins

Conditional Plugins can be activated in-code by using:
```php
wpcom_vip_load_plugin( 'plugin-name/plugin-file.php' )
```
