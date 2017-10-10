=== StatCounter ===

Contributors: littlebizzy
Tags: statcounter, statistics, stats, analytics, counter, traffic, data, tracking, code, javascript, js, snippet
Requires at least: 4.4
Tested up to: 4.8
Requires PHP: 7.0
Stable tag: 1.0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Prefix: STCNTR

Inserts StatCounter tracking code just above the closing body tag to ensure the fastest loading speed and to avoid conflicting with any other scripts.

== Description ==

Inserts StatCounter tracking code just above the closing body tag to ensure the fastest loading speed and to avoid conflicting with any other scripts.

Using this plugin will help you avoid errors on your site caused by the "official" version of StatCounter plugin for WordPress such as the Chrome console error below:

    (index):3834 A Parser-blocking, cross site (i.e. different eTLD+1) script, https://secure.statcounter.com/counter/counter.js, is invoked via document.write. The network request for this script MAY be blocked by the browser in this or a future page load due to poor network connectivity. If blocked in this page load, it will be confirmed in a subsequent console message.See https://www.chromestatus.com/feature/5718547946799104 for more details.
(anonymous) @ (index):3834

#### Compatibility ####

This plugin has been designed for use on LEMP (Nginx) web servers with PHP 7.0 and MySQL 5.7 to achieve best performance. All of our plugins are meant for single site WordPress installations only; for performance and security reasons, we highly recommend against using WordPress Multisite for the vast majority of projects.

#### Plugin Features ####

* Settings Page: Yes
* Upgrade Available: No
* Includes Media: No
* Includes CSS: No
* Database Storage: Yes
  * Transients: No
  * Options: Yes
* Database Queries: Backend Only
* Must-Use Support: Yes
* Multi-site Support: No
* Uninstalls Data: Yes

#### Code Inspiration ####

This plugin was partially inspired either in "code or concept" by the open-source software and discussions mentioned below:

[Official StatCounter Plugin](https://wordpress.org/plugins/official-statcounter-plugin-for-wordpress/)

#### Recommended Plugins ####

We invite you to check out a few other related free plugins that our team has also produced that you may find especially useful:

* [Google Analytics](https://wordpress.org/plugins/ga-littlebizzy/)
* [Server Status](https://wordpress.org/plugins/server-status-littlebizzy/)
* [Maintenance Mode](https://wordpress.org/plugins/maintenance-mode-littlebizzy/)

#### Special Thanks ####

We thank the following groups for their generous contributions to the WordPress community which have particularly benefited us in developing our own free plugins and paid services:

* [Automattic](https://automattic.com)
* [Delicious Brains](https://deliciousbrains.com)
* [Roots](https://roots.io)
* [rtCamp](https://rtcamp.com)
* [WP Tavern](https://wptavern.com)

#### Disclaimer ####

We released this plugin in response to our managed hosting clients asking for better access to their server, and our primary goal will remain supporting that purpose. Although we are 100% open to fielding requests from the WordPress community, we kindly ask that you keep the above mentioned goals in mind, thanks!

== Installation ==

1. Upload to the `/wp-content/plugins/sc-littlebizzy` directory
2. Activate via WP Admin > Plugins
3. Visit `/wp-admin/options-general.php?page=statcounter`

== Changelog ==

= 1.0.2 =
* minor code tweaks
* MUST RE-INPUT SETTINGS!

= 1.0.1 =
* removed noscript snippet (spammy)

= 1.0.0 =
* initial release
