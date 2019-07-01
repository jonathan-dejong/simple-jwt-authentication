=== Simple JWT Authentication ===
Contributors: jonathan-dejong
Donate link: http://fancy.to/scbk86
Tags: wp-rest, api, jwt, authentication, access
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 1.4.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Easily extends the WP REST API using JSON Web Tokens Authentication as an authentication method. Including ways to revoke access for users at any time.

== Description ==



== Installation ==

1. Upload `simple-jwt-authentication` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the settings page under Settings > Simple JWT Authentication to setup the plugin.

== Changelog ==
= 1.4 =
* Security fix - Not showing the secret key in WP admin if set as a constant. Thank you [JanThiel](https://github.com/JanThiel) for making me aware of this.
* Added the user data to the expire filter to allow for user specific expire times.
* Added a whole bunch of escaping and security improvements like nonces etc. Basically making the plugin follow WordPress-Extra standard instead of previous WordPress-Core.
* Bugfix - Fixed some issues with the token UI in profile pages. Thanks to [gordielachance](https://github.com/gordielachance) for making me aware of this.

= 1.3 =
* Merged PR allowing to refresh a token. Thanks to Qazsero@github.

= 1.0 =
* Initial version.
