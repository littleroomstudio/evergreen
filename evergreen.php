<?php
/**
 * Decisions, not options. Inspired by Trac ticket #58389 by
 * Jos Velasco and an offhanded comment on the fedi by Andy
 * Fragen.
 *
 * Plugin Name:        Evergreen
 * Plugin URI:         https://github.com/littleroomstudio/evergreen
 * GitHub Plugin URI:  https://github.com/littleroomstudio/evergreen
 * Description:        Enables auto-updates for plugins by default when plugins are installed or activated.
 * Version:            1.0.0
 * Author:             Little Room
 * Author URI:         https://littleroom.studio
 * License:            GPL-3.0-or-later
 * License URI:        https://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP:       8.3
 * Requires at least:  6.0
 * Text Domain:        evergreen
 *
 * @package Evergreen
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enable auto-updates for newly installed plugins.
 *
 * @param WP_Upgrader $upgrader   WP_Upgrader instance.
 * @param array       $hook_extra Array of bulk item update data.
 */
function evergreen_install_plugin( WP_Upgrader $upgrader, array $hook_extra ): void {
	if ( 'install' !== $hook_extra['action'] || 'plugin' !== $hook_extra['type'] ) {
		return;
	}

	$plugin_path = $upgrader->plugin_info();

	if ( ! $plugin_path ) {
		return;
	}

	evergreen_enable_auto_update( $plugin_path );
}
add_action( 'upgrader_process_complete', 'evergreen_install_plugin', 10, 2 );

/**
 * Enable auto-updates for activated plugins.
 *
 * @param string $plugin  The plugin file.
 * @param bool   $network Whether the plugin is being activated network-wide.
 */
function evergreen_activate_plugin( string $plugin, bool $network ): void {
	if ( $network ) {
		return;
	}

	evergreen_enable_auto_update( $plugin );
}
add_action( 'activate_plugin', 'evergreen_activate_plugin', 10, 2 );

/**
 * Enable auto-update for a plugin and track it.
 *
 * @param string $plugin The plugin file.
 */
function evergreen_enable_auto_update( string $plugin ): void {
	$current = get_option( 'auto_update_plugins', array() );

	if ( in_array( $plugin, $current, true ) ) {
		return;
	}

	$current[] = $plugin;
	update_option( 'auto_update_plugins', $current );

	// Track that this plugin was managed by Evergreen.
	$managed = get_option( 'evergreen_managed_plugins', array() );

	if ( ! in_array( $plugin, $managed, true ) ) {
		$managed[] = $plugin;
		update_option( 'evergreen_managed_plugins', $managed );
	}
}

/**
 * Clean up on uninstall - remove auto-update entries for plugins managed by Evergreen.
 */
function evergreen_uninstall(): void {
	$managed = get_option( 'evergreen_managed_plugins', array() );

	if ( ! empty( $managed ) ) {
		$auto_update_plugins = get_option( 'auto_update_plugins', array() );
		$auto_update_plugins = array_diff( $auto_update_plugins, $managed );
		update_option( 'auto_update_plugins', array_values( $auto_update_plugins ) );
	}

	delete_option( 'evergreen_managed_plugins' );
}
register_uninstall_hook( __FILE__, 'evergreen_uninstall' );
