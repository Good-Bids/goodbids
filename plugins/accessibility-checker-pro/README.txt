=== Accessibility Checker Pro ===
Contributors: equalizedigital, alh0319, stevejonesdev
Tags: accessibility, accessible, wcag, ada, a11y, section 508, audit, readability, content analysis
Requires at least: 6.2
Tested up to: 6.4.2
Stable tag: 1.6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


This plugin adds pro features to the Acessibility Checker plugin, including full site scanning.

== Description ==

Accessibility Check Pro is a premium plugin that expands the free Accessibility Checker available in the WordPress.org plugin directory.
Accessibility Checker Pro adds vital features that allow comprehensive accessibility audits to be run within your WordPress dashboard.
Features include:

* Full site scanning
* Ignore Log
* Bulk Ignore
* User restrictions on ignore
* Centralized list of all open issues
* Admin columns
* ...and more

Please note: Accessibility Checker Pro requires an active license to function. For a full list of features or to purchase a license, visit the 
[Accessibility Checker Pro website](https://my.equalizedigital.com).

== Installation ==
Getting started with Accessibility Checker Pro is as easy as installing and activating the plugin, then configuring a few basic settings.  

### Installing Accessibility Checker Pro Within WordPress
1. Visit the plugins page within your dashboard and select ‘Add New’.
2. On the Add Plugins screen, click the "Upload Plugin" button.
3. Upload the zipped plugin file from your download link.
4. Activate Accessibility Checker Pro from your Plugins page.
5. Follow the ‘after activation’ steps listed below.

### Installing Accessibility Checker Pro Manually

1.Upload the unzipped ‘accessibility-checker’ folder to the /wp-content/plugins/ directory on your website via FTP.
2. Activate Accessibility Checker Pro from your Plugins page.
3. Follow the ‘after activation’ steps listed below.

### After Activation
1. If you do not have Accessibility Checker Free installed and active, Accessibility Checker Pro will prompt you to install and activate it. Follow the prompts to install Accessibility Checker.
2. After activating the plugin, go to the Accessibility Checker settings page in your WordPress admin (found at '/wp-admin/admin.php?page=accessibility_checker_settings') to configure the 
plugin settings.

== Frequently Asked Questions ==

= What support is included? =

Equalize Digital provides free personalized support on plugin usage and features via email for all license holders. This includes information about functionality and
troubleshooting assistance. You may [open a support ticket](https://my.equalizedigital.com/support/pro-support/) at any time. Plugin support is limited to 
providing assistance in using the plugin but does not include support resolving any specific accessibility issues that have been identified.

= Can you help me figure out how to fix an error or warning on my website? =

If you have questions about specific errors and warnings on your website or need help fixing problems, we offer [priority support packages](https://my.equalizedigital.com/priority-support/)
that include full (phone and email) access to our accessibility specialists and developers. If you need more in-depth auditing, user testing, and remediation, or would like to 
get your website certified as accessible, [we can help with that too](https://equalizedigital.com/).

= Do I lose functionality if my license key is not active? =

To utilize Accessibility Checker Pro features, receive plugin updates, and support, you do need to have an active license key. If your license key has expired, you can 
continue to use the free version of Accessibility Checker but will lose access to Pro features.


== Changelog ==

= 1.6.0 =
* Added: scan_start, scan_reset and scan_progress WP Rest API calls to replace admin-ajax calls
* Added: start-new-scan rest call
* Moved: `edacp_scan_start` from accessibility-checker-pro to `EDACP\Scans::start_php_scan`
* Updated: `activation.php` to use `EDACP\Scans::start_php_scan`
* Deleted: CSS for log from full site scan page
* Moved: `getScanStats` code from `/src/fullSiteScanApp/checkPages.js` to `/src/fullSiteScanApp/fullSiteScanUI.js`
* Deleted: jquery from `/src/fullSiteScanApp/fullSiteScanUI.js`
* Added: a page reload every ten minutes to full site scan a page
* Deleted: setting authorization header in rest calls
* Fixed:  mismatched number of URLs scanned
* Fixed: type in global ignore title
* Fixed: pluralization of full site summary stats

= 1.5.2 =
* Added: Audit History 1.0.0 and 1.0.1 update fix
* Fixed: variable casting

= 1.5.1 =
* Fixed: open issues descriptions output

= 1.5.0 =
* Fixed: Issues with JavaScript assets not loading
* Updated: Full-site scan now supports JavaScript scanning
* Added: 'Stop' button for full-site scanning
* Updated: Posts to scan function optimized for paging and using only IDs
* Removed: 'accessibility_checker_logs' database table
* Added: Code improvements in the database update function
* Removed: Full site scan log to enhance performance
* Removed: Background scan schedule to align with new full site scanning
* Updated: Full site scan completion message updated to reflect log removal

= 1.4.3 =
* Updated: version and readme

= 1.4.2 =
* Updated: readme

= 1.4.1 =
* Fixed: delete all data on uninstall option
* Fixed: misnamed authorization options and added migration function
* Update: password protection to pass authorization to wp-cron
* Fixed: expired license notice after key renews
* Added: image column to individual open issues and fast track
* Fixed: incorrect fieldname "objectid" in SQL call when clicking affected code in the table header on the issue log.

= 1.4.0 =
* Fixed: adherence to coding standards
* Fixed: full-site scan stop button issue
* Fixed: settings page tab order
* Fixed: full-site scan error when no custom post types are configured for scanning in settings
* Fixed: activation issue when no custom post types are designated for scanning in settings
* Added: warnings when no custom post types are configured for scanning in settings
* Updated: full-site scan completion date now uses site's localized timezone
* Updated: refresh and caching for reports dashboard widget and welcome page

= 1.3.1 =
* Fixed - scan log output
* Fixed - ignore log not filtering by issue

= 1.3.0 =
* Added: plugin architecture for converting PHP checks to JavaScript
* Added: JavaScript scanning for color contrast check
* Updated: full-site scan with a secondary JavaScript pass
* Updated: full-site scan log output for better performance
* Added: full-site scan stop button
* Updated: open issues table with accessibility improvements
* Added: frontend highlighting view on-page links

= 1.2.9 =
* Fixed: activation issue with the valid table name function and the dependency installer

= 1.2.8 =
* Fixed: issue with zip file name

= 1.2.7 =
* Added: filterable query limit to the full site scan log
* Updated: full site scan to only run if a custom post type is selected in the settings
* Updated: full site scan to delete data for post types no longer selected in the settings
* Fixed: bug with global ignores being added to all individually ignored issue count
* Fixed: usort deprecated notice on ignore log

= 1.2.6 =
* Fixed: open issues with no errors to show as passed
* Updated: Action Scheduler to latest version
* Update: Action Scheduler to load via Composer

= 1.2.5 =
* Fixed: post type count on full size scan

= 1.2.4 =
* Updated: admin notice output

= 1.2.3 =
* Added: license tab
* Updated: license check to run via cronjob
* Updated: EDD license class

= 1.2.2 =
* Added: security updates

= 1.2.1 =
* Added: Support for the Restricted Access Plugin
* Added: Password protection settings

= 1.2.0 =
* Update: Heading order on Open Issues, Fast Track, Ignore Log and Global Ignores pages
* Added: Initial rule list page ordered by count to the Open Issues and Ignore Log pages
* Added: Individual rule pages with count, issue summary and rule select to quickly switched between issues to the Open Issues and Ignore Log pages
* Added: Custom Post Type select to Fast Track to reduce query load and improve performance

= 1.1.9 =
* Update dependency installer to latest version fixing dismiss-notice.js 404

= 1.1.8 =
* Update log filter admin url

= 1.1.7 =
* Show Open Issues and Ignore Log admin pages to users with ignore permissions

= 1.1.6 =
* Add support for PHP 8

= 1.1.5 =
* Move accessibility statement free plugin

= 1.1.4 =
* Update license domain and product name

= 1.1.3 =
* Improvements to license activation/deactivation settings

= 1.1.2 =
* Add support for Accessibility New Window Warning Plugin
* Plugin dependency installer

= 1.1.1 =
* Fix to allow ignores database to be deleted on uninstall

= 1.1.0 =
* Fast Track

= 1.0.3 =
* Updates to database creation/updates
* Updates to plugin updater
* Deprecated jQuery fixes

= 1.0.2 =
* Full site scan and background scan

= 1.0.1 =
* Removed content hook
* Fixed issue with log search if post type has been Removed
* Fixed duplicate column names in screen options

= 1.0.0 =
* Everything is new and shiny.
* We think it's awesome you want to make your website more accessible.
* Thank you for purchasing Accessibility Checker Pro.

