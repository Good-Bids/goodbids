## Installing Plugins

We are using [Composer](https://getcomposer.org/) and [WPackagist](https://wpackagist.org/) to manage our plugins. To install a new plugin that exists in the WordPress Plugin Repository, run the following command:

```sh
composer require wpackagist-plugin/plugin-name
```

Once added to composer, update the main `.gitignore` file to exclude the plugin from version control.

### Custom Plugins

If a plugin does not exists in the WordPress Plugin Repository at [WPackagist](https://wpackagist.org/) and can not be install with composer. You will need to add the plugin to the `client-mu-plugins` folder.
To load the custom plugin, go to `plugin-loader.php` and add:

```php
// [The name/title of the plugin ]
$[plugin_name]_plugin_path = WPCOM_VIP_CLIENT_MU_PLUGIN_DIR . '/[plugin_folder]/[plugin_start_file].php';
if ( file_exists( $oauth_plugin_path ) ) {
	require_once $[plugin_name]_plugin_path;
}
```
