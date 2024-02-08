## Installing Plugins

We are using [Composer](https://getcomposer.org/) and [WPackagist](https://wpackagist.org/) to manage our plugins. To install a new plugin that exists in the WordPress Plugin Repository, run the following command:

```sh
composer require wpackagist-plugin/plugin-name
```

Once added to composer, update the main `.gitignore` file to exclude the plugin from version control.

### Custom/Premium Plugins

Plugins that do not exist in WPackagist can be added manually by placing the plugin folder directly in the `plugins` directory and updating `config.json`.
