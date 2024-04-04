## Installing Plugins

We are using [Composer](https://getcomposer.org/) and [WPackagist](https://wpackagist.org/) to manage our plugins. To install a new plugin that exists in the WordPress Plugin Repository, run the following command when in the `client-mu-plugins/goodbids` directory:

```sh
composer require wpackagist-plugin/plugin-name
```
#### Auto-activating
To have a plugin auto-activated, add it to the `active-plugins` property in `client-mu-plugins/goodbids/config.json`. 

If the filename is different from the plugin directory name, you will need to specify the directory and file:
```json
{
    "active-plugins":[
    "advanced-custom-fields-pro/acf.php",
    ]
}
```

### Custom/Premium Plugins

Plugins that do not exist in [WPackagist](https://wpackagist.org/) can be added manually by placing the plugin folder directly in the `plugins` directory.

You will also need to update the main `.gitignore` file to exclude the plugin being ignore so it can be added into version control.

## Continue Reading

* [Activating Plugins](activating.md)
