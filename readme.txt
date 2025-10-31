=== Admin Bar Theme & Plugin Switcher ===
Contributors: devninja-wp
Tags: admin bar, theme switcher, plugin manager, development, ajax
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0
License: MIT
License URI: LICENSE

Instantly switch themes and activate/deactivate plugins from the WordPress Admin Bar for faster development and testing.

== Description ==

Admin Bar Theme & Plugin Switcher is a developer-friendly tool that allows you to:

* Switch between installed themes instantly via AJAX.
* Activate or deactivate plugins individually without leaving the current page.
* Deactivate all plugins with one click for debugging purposes.
* See the number of active plugins directly in the Admin Bar.
* Work on both the admin dashboard and the frontend (for users with sufficient permissions).

This plugin is perfect for developers and site maintainers who frequently need to test different themes and plugins without going through the WordPress admin menus.

---

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Ensure you have **Administrator** privileges to use theme and plugin switching.
4. The "Theme Switcher" and "Plugins" menu will appear in the Admin Bar.

---

== Frequently Asked Questions ==

= Who can use this plugin? =

Only users with `switch_themes` and `activate_plugins` capabilities (usually Administrators) can access the menus.

= Can I use this on the frontend? =

Yes! The Admin Bar menus will appear on the frontend for logged-in users with the correct permissions.

= Does this plugin modify my themes or plugins permanently? =

Yes, switching a theme or activating/deactivating a plugin via this tool updates your site just like using the regular WordPress menus. Always use caution in production environments.

= Can I deactivate all plugins and then reactivate them later? =

Currently, only "Deactivate All" is supported. Reactivating plugins individually can be done through the plugin list in the menu or WordPress admin.

---

== Screenshots ==

1. Theme Switcher menu in the Admin Bar.
2. Plugin Switcher menu with active plugin count.
3. Deactivate All Plugins link in the menu.

---

== Changelog ==

= 1.0 =
* Initial release
* Added Admin Bar Theme Switcher with AJAX support
* Added Plugin Manager with individual plugin activation/deactivation
* Added "Deactivate All Plugins" feature
* Active plugin count displayed in menu

---

== Upgrade Notice ==

= 1.0 =
Initial release for developers who need faster theme and plugin switching.

---

== License ==

This plugin is licensed under the MIT License.
