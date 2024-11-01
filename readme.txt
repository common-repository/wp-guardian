=== WP Guardian ===
Contributors: butterflymedia
Donate link: https://www.buymeacoffee.com/wolffe
Tags: security, firewall, malware, attack, hack
Requires at least: 4.9
Tested up to: 6.6.2
Requires PHP: 7.0
Stable tag: 1.5.2
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

An easy way to harden your website's security effectively.

== Description ==

WP Guardian is a simple but effective plugin that locks down your WordPress website to ensure it's protected and safe.

= About =

Using this plugin couldn't be easier as it's designed to be as straight forward as possible to make sure you can get your website safe and secure so you can get on with more important things. WP Guardian includes features such as a powerful firewall and Two-Step verification for logging in.

== Installation ==

1. Download the plugin package.
2. Upload to the /wp-content/plugins/ directory.
3. Activate the plugin in the dashboard.
4. Go to the settings page and configure the plugin to get started.

== Frequently Asked Questions ==

= How does this plugin secure my WordPress site? =

This plugin helps secure your website by locking things down with a range of effective tools with a simple interface.

= How good is the security of this plugin? =

The plugin is by no means a one stop solution for everything. It's designed to be simple, giving you a range of essential security features to harden your site's security defences.

= How does Two-Step verification work? =

The option is available on your profile page when enabled globally. It will let you choose a method in which a code is sent to a secondary location for you to enter at login before you can authenticate.

== Changelog ==

= 1.5.2 =
* Add new brute force protection feature
* Update WordPress compatibility

= 1.5.1 =
* Add option to restrict external POST requests
* Clean up the plugin Dashboard

= 1.5.0 =
* Clean up the admin stylesheet
* Remove the inefficient login lockdown feature
* Remove several obsolete features (the plugin is 8 years old, after all)
* Refactor the Settings page to use the native settings API, instead of jQuery accordions
* Rebrand Gatekeeper to Guardian

= 1.4.6 =
* Fix the firewall module being requested twice
* Implement a better Dashboard section
* Add default settings for the firewall to make it truly plug and play
* Make blocked requests count more prominent
* Set foundation for SQLite lockout logging
* Remove the aside section from the Dashboard

= 1.4.5 =
* Implement logging for malicious requests
* Implement log pruning
* Move all security settings to a new tab
* Refactor the Dashboard tab

= 1.4.4 =
* Remove broken automatic core updates options
* Remove unused constants
* Fix request logging function
* Add malicious request counter
* Add new Settings tab
* Move Settings page to the Settings tab
* Ignore AJAX and REST requests in the firewall

= 1.4.3 =
* Fix issues with the firewall (for good)
* Remove obsolete features, such as database backups and version control

= 1.4.2 =
* Fix issues with pattern matching in the firewall

= 1.4.1 =
* Add new firewall feature
* Add new security settings
* Sanitize and escape all data

= 1.4.0 =
* Fix the plugin header information (stable tag, tested up to, etc.)
* Implement WordPress Coding Standards (WPCS)
* Replace index.html with index.php in the root directory
* Remove changelog.md file and move contents to readme.txt
* Remove readme.md file and move contents to readme.txt

= 1.3.4 = 
* More updates to author information 

= 1.3.3 = 
* Updated author information - Removed Daniel James danieltj
* To see the full revision history, please read the `CHANGELOG.md` file which explains any changes that have been made.

= 1.3.2 =
* Released: 19th November 2017
* Fixed a mistake listed in the change log file.
* Removed the button links to security and settings next to the page titles.

= 1.3.1 =
* Released: 19th November 2017
* Fixed a bug where database backups weren't attached to emails.
* Improved some of the transaltion strings throughout the plugin.

= 1.3.0 =
* Released: 13th November 2017
* Added the ability to remove support for Emoji scripts.
* Updated how CSS and JS assets are loaded for users.
* Updated a few language translation strings.

= 1.2.2 =
* Released: 8th November 2017
* Fixed a bug which caused some meta data to not be removed properly.
* Improvements to a range of code documentation and meta data.

= 1.2.1 =
* Released: 26th October 2017
* Added a new directory within the uploads folder for database backups.
* Improvements to database backup function and supporting documentation.
* Improvements to the database upgrade notice and l10n strings.
* Removed a deprecated callback function for the settings section.
* Removes the user meta for Two Step Verification when the plugin is deleted.

= 1.2.0 =
* Released: 16th October 2017
* Added new field to send backup emails to different email address.
* Added a new setting to allow WP_DEBUG to be enabled/disabled via the dashboard.
* Improved the Version Control page to now include update information.
* Improved the CSS styling for the plugin user interface.
* Improved overall code base and removed unused functions that are no longer used.
* Improved some language strings and corrected a sentence that wasn't translatable.

= 1.1.0 =
* Released: 12th October 2017
* Fixed the implementation of Two Step Verification code expiry.
* Improvements to core functions and inline documentation.
* Improved some of the language strings and provided more context.
* Updated the server config to include some essential rewrite rules.
* Updated the readme.txt file with more useful information.

= 1.0.0 =
* Released: 11th October 2017
* Initial version
