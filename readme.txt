=== MQ Permalinks ===

Tags: permalink, url, link, address, custom, redirect
Contributors: Adrian Kuehnis
Requires at least: 3.5.1
Tested up to: 3.5.1
Stable tag: 0.1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Create modification-secure permalinks for posts, pages and categories

== Description ==

MQ Permalinks is a plugin for the wordpress blog to make permalinks modification-secure. Changing blog name, page name or category slug creates broken links on external pages because the url for a specific page is changed.

MQPermalinks plugin creates modification-secure permalinks as follows:

 * p57-postname
 * a88-pagename
 * c2-categoryslug

Even if the names are modified for SEO reasons, older links will stay valid. Furthermore, if the page structure is modified, the permalinks for pages stay the same.

Example:

Above link urls can be uptimized for *keyword* as follows, leaving the old links still working:

 * p57-*keyword*-postname
 * a88-*keyword*-pagename
 * c2-*keyword*-categoryslug

Note:

Changing a post name, page name or category slug, creates a new URL with identical content. Wordpress, however, includes a canonical link into the head section of the page telling search machines and browsers the correct URL for the page. 


== Installation ==

1. Unzip the package, and upload `mqpermalinks` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

Note: 

The plugin creates a .htaccess file in the root directory

The plugin sets permalink structure option to default and disables the main menu link "change permalinks"

== Changelog ==

= 0.1.4 =

 * bugfix: preview links in backend now working  

= 0.1.3 =
 
 * edit page slug and post name enabled


= 0.1.2 =

 * readme file added
