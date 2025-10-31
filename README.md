# Admin Bar Theme & Plugin Switcher

Instantly switch themes and activate/deactivate plugins from the WordPress Admin Bar for faster development and testing.

## Features

- **Theme Switcher:** Quickly switch between installed themes via AJAX.
- **Plugin Manager:** Activate or deactivate plugins individually without leaving the page.
- **Deactivate All Plugins:** One-click deactivation for debugging purposes.
- **Active Plugin Count:** Displays the number of active plugins in the Admin Bar.
- Works on both the **admin dashboard** and the **frontend** for users with sufficient permissions.

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Ensure you have **Administrator** privileges.
4. Look for the **Theme Switcher** and **Plugins** menu in the Admin Bar.

## Usage

- **Switch Themes:** Click the Theme Switcher menu and select a theme. The theme will switch instantly via AJAX.
- **Activate/Deactivate Plugins:** Click a plugin in the Plugins menu to toggle its activation status.
- **Deactivate All Plugins:** Use the "Deactivate All Plugins" link at the top of the Plugins menu.

## Screenshots

1. Theme Switcher menu in the Admin Bar.
2. Plugin Switcher menu with active plugin count.
3. "Deactivate All Plugins" link in the menu.

## Frequently Asked Questions (FAQ)

**Q: Who can use this plugin?**  
A: Only users with `switch_themes` and `activate_plugins` capabilities (usually Administrators).

**Q: Can I use this on the frontend?**  
A: Yes! The Admin Bar menus will appear on the frontend for logged-in users with the correct permissions.

**Q: Does this plugin modify my themes or plugins permanently?**  
A: Yes, switching a theme or activating/deactivating a plugin via this tool updates your site just like using the regular WordPress menus. Use caution in production environments.

**Q: Can I deactivate all plugins and then reactivate them later?**  
A: Currently, only "Deactivate All" is supported. Reactivation can be done individually via the menu or the WordPress plugin page.

## Changelog

### 1.0
- Initial release  
- Admin Bar Theme Switcher with AJAX support  
- Plugin Manager with individual plugin activation/deactivation  
- "Deactivate All Plugins" feature  
- Active plugin count displayed in menu

## License

This plugin is licensed under the MIT License.  
