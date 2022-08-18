=== Easy Multilevel Nav Menu ===
Contributors: byvex
Tags: menu, navigation, multilevel, mega menu
Requires at Least: 5.0
Tested Up To: 6.0
Requires PHP: 7.0
Stable tag: 0.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily create multi-level responsive nav menu with simple shortcode.

== Description ==

Easily create multi-level responsive nav menu with simple shortcode.

- No use !important in styles, so you can easily change styles.
- No use of database to increase speed.
- Easily customizable with shortcode.
- Independent menu toggle button, can be placed anywhere on page.
- No jQuery dependency

You can create multilevel responsive menus. To show menu, you will need 2 shortcodes; one is for the menu and another is for the button to toggle menu below responsive breakpoint.

For Displaying Menu:

`[easy_multi_menu name="Primary Menu" breakpoint="992" z-index="101" sidebar-width="285" bg-color="#181818" text-color="#eee"]`

For Displaying Menu Button:

`[easy_multi_menu_btn name="Primary Menu" class="btn btn-primary" title="Open Menu"] <i class="fa-solid fa-bars"></i> [/easy_multi_menu_btn]`

== Installation ==

= Installing from WordPress repository: =

1. From the dashboard of your site, navigate to Plugins –> Add New.
2. In the Search type Easy Multilevel Nav Menu
3. Click Install Now
4. When it’s finished, activate the plugin via the prompt. A message will show confirming activation was successful.

Make sure that your server has php version greater or equal to 7.0, otherwise, the plugin won't activate.

== Frequently Asked Questions ==

= Where can I report bugs? =
Report bugs on the [Easy Multilevel Nav Menu GitHub repository](https://github.com/byvex/easy-multilevel-nav-menu/issues/new).

== Changelog ==

= 0.0.2 =
* first release
