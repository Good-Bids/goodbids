# GoodBids API: Network Sites

## Core Methods

`goodbids()->sites->get_np_data( string $site_id, string $field_id )`  
Returns the custom Nonprofit data for the given network site. If `$field_id` is provided, only that field will be returned, otherwise all fields will be returned.

`goodbids()->sites->get_np_fields( string $context )`  
Returns the array of custom fields, based on the given context (create, edit, or both).

`goodbids()->sites->get_privacy_policy_link()`  
Returns a link for the network privacy policy link html as a string

`goodbids()->sites->get_terms_conditions_link()`
Returns a link for the network terms conditions link html as a string
