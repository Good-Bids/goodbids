# GoodBids

## Getting Started

- [Local installation Guide](local.md)
- [Git Workflow](workflow.md)

## Installing Plugins

We are using [Composer](https://getcomposer.org/) and [WPackagist](https://wpackagist.org/) to manage our plugins. To install a new plugin that exists in the WordPress Plugin Repository, run the following command:

```sh
composer require wpackagist-plugin/plugin-name
```

### Activating Plugins

Plugins can be activated in-code by using:
```php
wpcom_vip_load_plugin( 'plugin-name/plugin-file.php' )
```

You can add to the list of active plugins in `client-mu-plugins/goodbids/src/classes/Core.php` 
