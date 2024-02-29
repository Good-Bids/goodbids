/**
 * Returns the base URL of the admin section.
 * Used to create links in both the subdomain and subdirectory setups.
 *
 * @returns {string} The base URL of the admin section.
 */

export function getBaseAdminUrl() {
	return window.location.href.replace(/\/wp-admin\/.*/g, '');
}
