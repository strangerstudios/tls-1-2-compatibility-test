=== TLS 1.2 Compatibility Test ===
Contributors: strangerstudios
Tags: security, ecommerce, gateway, paypal, tls, server, php, version, curl, sslversion
Requires at least: 3.0
Tested up to: 4.5.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Verify TLS 1.2 support for included API endpoints and diagnose a solution to enable compatibility.

== Description ==

Payment gateways are now requiring commmunication via TLS 1.2. This plugin will test your webserver with popular gateways such as Stripe, PayPal, Braintree and Authorize.net, as well as the testing endpoint provided by OpenSSL to ensure there is no outage in your ecommerce application. 

If your server is not able to communicate via the TLS 1.2 protocol, you will be shown the appropriate steps to take to upgrade your server's version of OpenSSL, PHP, or direct you to update the SSLVERSION of CURL.
 
== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `tls-1-2-compatibility-test` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to Tools > TLS 1.2 Compatibility Test to begin.

== Frequently Asked Questions ==

None yet.

== Changelog ==
= 1.0 =
* First release!