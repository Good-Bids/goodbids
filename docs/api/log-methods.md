# GoodBids API: Log

## Core Methods

`Log::info( string $message, array $context )`  
Logs an info message.  

`Log::warning( string $message, array $context )`  
Logs a warning message.  

`Log::error( string $message, array $context )`  
Logs an error message.  

`Log::debug( string $message, array $context )`  
Logs a debug level message.  

`Log::alert( string $message, array $context )`  
Logs an alert level message.  

`Log::critical( string $message, array $context )`  
Logs a critical level message.

`Log::emergency( string $message, array $context )`  
Logs a emergency level message.

`Log::convert_php_level_to_string( int $error_code )`  
Converts the PHP error code to a readable string.
