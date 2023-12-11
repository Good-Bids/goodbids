# GoodBids Plugin

A plugin that powers the GoodBids.org site.

## GoodBids API

This is a quick reference of some of the functions available in the GoodBids API.

Call the API using the `goodbids()` function.

### Core Functions

`goodbids()->get_version()`  
Returns the current version of the GoodBids plugin.

`goodbids()->get_config( string $setting )`  
Returns the config value for the given setting.

`goodbids()->is_plugin_active( string $plugin )`  
Checks if the given plugin (slug) is active. This is not the same as the WordPress `is_plugin_active()` function, all it does is checks if the plugin is in the active_plugins config.

### Network Sites Functions

`goodbids()->sites->get_np_data( string $site_id, string $field_id )`  
Returns the custom Nonprofit data for the given network site. If `$field_id` is provided, only that field will be returned, otherwise all fields will be returned.

`goodbids()->sites->get_np_fields( string $context )`  
Returns the array of custom fields, based on the given context (create, edit, or both).

### Admin Functions

`goodbids()->admin->render_field( string $key, array $field, string $prefix, array $data )`  
Renders an admin field based on the given field array. The `$prefix` and `$data` parameters are optional, but required for some field types.

### Auction Functions

`goodbids()->auctions->get_post_type()`  
Returns the auction post type slug.
