# GoodBids API

## Core Methods

`goodbids()->get_version()`  
Returns the current version of the GoodBids plugin.

`goodbids()->get_config( string $setting )`  
Returns the config value for the given setting. You can use dot notation to access nested values.

`goodbids()->is_plugin_active( string $plugin )`  
Checks if the given plugin (slug) is active. This is not the same as the WordPress `is_plugin_active()` function, all it does is checks if the plugin is in the active_plugins config.

`goodbids()->is_dev_env()`  
Checks if the current environment is a development environment.
