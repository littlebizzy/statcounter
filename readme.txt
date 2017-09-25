=== StatCounter ===

Contributors: littlebizzy
Tags: statcounter, statistics, stats, analytics, counter, traffic, data, tracking, code, javascript, js, snippet
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 1.0.1
License: GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Inserts StatCounter tracking code just above the closing body tag to ensure the fastest loading speed and to avoid conflicting with any other scripts.

== Description ==

Inserts StatCounter tracking code just above the closing body tag to ensure the fastest loading speed and to avoid conflicting with any other scripts.

Using this plugin will help you avoid errors on your site caused by the "official" version of StatCounter plugin for WordPress such as the Chrome console error below:

(index):3834 A Parser-blocking, cross site (i.e. different eTLD+1) script, https://secure.statcounter.com/counter/counter.js, is invoked via document.write. The network request for this script MAY be blocked by the browser in this or a future page load due to poor network connectivity. If blocked in this page load, it will be confirmed in a subsequent console message.See https://www.chromestatus.com/feature/5718547946799104 for more details.
(anonymous) @ (index):3834

== Installation ==

1. Upload to the "/wp-content/plugins/" directory
2. Activate the plugin through the "Plugins" menu in WordPress

== Changelog ==

= 1.0.1 =
* removed noscript snippet (spammy)

= 1.0.0 =
* initial release
