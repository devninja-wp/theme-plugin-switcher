<?php
/**
 * Plugin Name: Admin Bar Theme & Plugin Switcher
 * Description: Dev Tool, that will tweaks your admin bar menu with adding option to toggle themes and deactivate/activate plugin instantly.
 * Version: 1.0.0
 * Author: Dev Ninja WP
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Tested up to: 6.8
 *
 * @package TP_Switcher
 * @author WP Tripzzy
 */

/**
 * Add Theme & Plugin Switcher menus to Admin Bar.
 */
add_action( 'admin_bar_menu', 'tps_add_dev_tools_menu', 100 );

/**
 * Callback to Add Theme & Plugin Switcher menus to Admin Bar.
 *
 * @param object $admin_bar Admin bar object.
 * @return void
 */
function tps_add_dev_tools_menu( $admin_bar ) {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$current_theme = wp_get_theme();

	// 🎨 THEME SWITCHER
	$admin_bar->add_menu(
		array(
			'id'    => 'tp-theme-switcher',
			'title' => '🎨 Theme (' . esc_html( $current_theme->get( 'Name' ) ) . ')',
			'href'  => '#',
		)
	);

	foreach ( wp_get_themes() as $slug => $theme ) {
		$is_active = ( $theme->get_stylesheet() === $current_theme->get_stylesheet() );
		$admin_bar->add_menu(
			array(
				'parent' => 'tp-theme-switcher',
				'id'     => 'tp-theme-' . esc_attr( $slug ),
				'title'  => ( $is_active ? '✅ ' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' ) . esc_html( $theme->get( 'Name' ) ),
				'href'   => '#',
				'meta'   => array(
					'class' => 'tp-theme-item' . ( $is_active ? ' active-theme' : '' ),
				),
			)
		);
	}

	// ⚙️ PLUGIN SWITCHER
	$all_plugins    = get_plugins();
	$active_plugins = get_option( 'active_plugins', array() );
	$active_count   = count( $active_plugins );
	$total_plugins  = count( $all_plugins );

	$admin_bar->add_menu(
		array(
			'id'    => 'tp-plugin-switcher',
			'title' => sprintf( '⚙️ Plugins (%d/%d)', $active_count, $total_plugins ),
			'href'  => '#',
		)
	);

	// Add "Deactivate All" option.
	$admin_bar->add_menu(
		array(
			'parent' => 'tp-plugin-switcher',
			'id'     => 'tp-plugin-deactivate-all',
			'title'  => '❌ Deactivate All Plugins',
			'href'   => '#',
			'meta'   => array(
				'class' => 'tp-plugin-deactivate-all',
			),
		)
	);

	foreach ( $all_plugins as $plugin_file => $plugin_data ) {
		$is_active = in_array( $plugin_file, $active_plugins, true );
		$admin_bar->add_menu(
			array(
				'parent' => 'tp-plugin-switcher',
				'id'     => 'tp-plugin-' . md5( $plugin_file ),
				'title'  => ( $is_active ? '🟢 ' : '⚪ ' ) . esc_html( $plugin_data['Name'] ),
				'href'   => '#',
				'meta'   => array(
					'class' => 'tp-plugin-item' . ( $is_active ? ' active-plugin' : '' ),
					'title' => $plugin_file,
				),
			)
		);
	}
}

/**
 * Inject data attributes for JS.
 */
add_action( 'admin_head', 'tps_add_data_attributes_script' );
add_action( 'wp_head', 'tps_add_data_attributes_script' );

/**
 * Callback to Inject data attributes for JS.
 *
 * @return void
 */
function tps_add_data_attributes_script() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	?>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Add data-theme
		document.querySelectorAll('#wp-admin-bar-tp-theme-switcher .ab-item').forEach(function(el) {
			let id = el.parentElement.id.match(/^wp-admin-bar-tp-theme-(.+)$/);
			if (id) el.dataset.theme = id[1];
		});

		// Add data-plugin
		document.querySelectorAll('#wp-admin-bar-tp-plugin-switcher .ab-item').forEach(function(el) {
			let title = el.getAttribute('title');
			if (title) el.dataset.plugin = title;
		});
	});
	</script>
	<?php
}

/**
 * AJAX: Switch Theme
 */
add_action(
	'wp_ajax_tps_switch_theme',
	function () {
		if ( ! current_user_can( 'switch_themes' ) ) {
			wp_send_json_error( __( 'Permission denied', 'textdomain' ) );
		}
		check_ajax_referer( 'tps_dev_tools_nonce', 'security' );
		if ( ! isset( $_POST['theme'] ) ) {
			wp_send_json_error( 'Invalid theme' );
		}

		$theme = sanitize_text_field( wp_unslash( $_POST['theme'] ) ?? '' );
		if ( ! wp_get_theme( $theme )->exists() ) {
			wp_send_json_error( 'Invalid theme' );
		}
		switch_theme( $theme );
		wp_send_json_success( 'Theme switched to ' . wp_get_theme( $theme )->get( 'Name' ) );
	}
);

/**
 * AJAX: Toggle Plugin (Activate / Deactivate)
 */
add_action(
	'wp_ajax_tps_toggle_plugin',
	function () {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( __( 'Permission denied', 'textdomain' ) );
		}
		check_ajax_referer( 'tps_dev_tools_nonce', 'security' );
		if ( ! isset( $_POST['plugin'] ) ) {
			wp_send_json_error( 'Invalid plugin' );
		}

		$plugin = sanitize_text_field( wp_unslash( $_POST['plugin'] ) ?? '' );
		if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
			wp_send_json_error( 'Invalid plugin' );
		}

		if ( is_plugin_active( $plugin ) ) {
			deactivate_plugins( $plugin );
			wp_send_json_success( 'Deactivated: ' . $plugin );
		} else {
			$result = activate_plugin( $plugin );
			if ( is_wp_error( $result ) ) {
				wp_send_json_error( $result->get_error_message() );
			}
			wp_send_json_success( 'Activated: ' . $plugin );
		}
	}
);

/**
 * AJAX: Deactivate All Plugins
 */
add_action(
	'wp_ajax_tps_deactivate_all_plugins',
	function () {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( __( 'Permission denied', 'textdomain' ) );
		}
		check_ajax_referer( 'tps_dev_tools_nonce', 'security' );

		deactivate_plugins( get_option( 'active_plugins', array() ) );

		wp_send_json_success( 'All plugins have been deactivated.' );
	}
);

/**
 * JS for AJAX actions
 */
add_action( 'admin_enqueue_scripts', 'tps_enqueue_dev_tools_script' );
add_action( 'wp_enqueue_scripts', 'tps_enqueue_dev_tools_script' );

/**
 * Callback for JS for AJAX actions.
 *
 * @return void
 */
function tps_enqueue_dev_tools_script() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$localized = array( 'ajax_url' => admin_url( 'admin-ajax.php' ) );
	wp_localize_script( 'jquery', 'tps_theme_switcher', $localized );
	wp_enqueue_script( 'jquery' );

	wp_add_inline_script(
		'jquery',
		"
		jQuery(document).on('click', '#wp-admin-bar-tp-theme-switcher .ab-item[data-theme]', function(e){
			e.preventDefault();
			let el = jQuery(this), theme = el.data('theme');
			if (el.closest('li').hasClass('active-theme')) return;
			if (!confirm('Switch to theme: ' + theme + '?')) return;
			jQuery.post(tps_theme_switcher.ajax_url, {
				action: 'tps_switch_theme',
				theme: theme,
				security: '" . wp_create_nonce( 'tps_dev_tools_nonce' ) . "'
			}, function(r){
				// alert(r.data);
				if (r.success) location.reload();
			});
		});

		jQuery(document).on('click', '#wp-admin-bar-tp-plugin-switcher .ab-item[data-plugin]', function(e){
			e.preventDefault();
			let el = jQuery(this), plugin = el.data('plugin');
			let actionText = el.closest('li').hasClass('active-plugin') ? 'Deactivate' : 'Activate';
			if (!confirm(actionText + ' plugin: ' + plugin + '?')) return;
			jQuery.post(tps_theme_switcher.ajax_url, {
				action: 'tps_toggle_plugin',
				plugin: plugin,
				security: '" . wp_create_nonce( 'tps_dev_tools_nonce' ) . "'
			}, function(r){
				// alert(r.data);
				if (r.success) location.reload();
				else {
					alert( r.data );
				}
			});
		});

		// Deactivate All Plugins
		jQuery(document).on('click', '#wp-admin-bar-tp-plugin-deactivate-all .ab-item', function(e){
			e.preventDefault();
			if (!confirm('Are you sure you want to deactivate ALL plugins?')) return;
			jQuery.post(tps_theme_switcher.ajax_url, {
				action: 'tps_deactivate_all_plugins',
				security: '" . wp_create_nonce( 'tps_dev_tools_nonce' ) . "'
			}, function(r){
				alert(r.data);
				if (r.success) location.reload();
			});
		});
	"
	);
}
