=== TLS 1.2 Compatibility Test ===
Contributors: strangerstudios
Tags: security, ecommerce, gateway, paypal, tls, server, php, api, version, curl, sslversion
Requires at least: 3.0
Tested up to: 5.2.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Verify TLS 1.2 support on your webserver for included API endpoints and diagnose a solution to enable compatibility.

== Description ==

Payment gateways are now requiring commmunication via TLS 1.2. This plugin will test your webserver for compatibility to ensure there is no outage in your ecommerce application.

If your server is not able to communicate via TLS 1.2, you will be shown the appropriate steps to take to upgrade the server version of OpenSSL, PHP, or direct you to update the SSLVERSION of CURL.

The plugin currently offers testing via these API Endpoints:
* PayPal (https://tlstest.paypal.com/)
* How's My SSL? (https://www.howsmyssl.com/a/check)

Testing against these API Endpoints should validate compatibility for other gateways and APIs, even if you are not using PayPal, for example, on your ecommerce application. However, we do plan to add more tests for API Endpoints provided by popular gateways.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `tls-1-2-compatibility-test` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to Tools > TLS 1.2 Compatibility Test to begin.

== Frequently Asked Questions ==

None yet.

== Screenshots ==

1. Admin page under Tools > TLS 1.2 Test.

== Changelog ==
= 1.0.1 =
* Added cURL version to the table with notes to upgrade if below 7.34.0.
* Improved recommendations in the notes section.
* Removed Google as an endpoint since it's testing other things besides TLS 1.2 and doesn't have a way to parse the response.
* Removed the "rating" in the notes for How's My SSL. How's My SSL returns a lot of data. I'm trying to figure out how to share it.
* Added link to the PMPro blog post describing TLS 1.2.
* Updated descriptions in the sidebar/etc.
* Added banner to repository page.

= 1.0 =
* First release!
