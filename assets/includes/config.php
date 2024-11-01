<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

function dtjwpg_plugin_install() {
    // Core Plugin Settings
    delete_option( 'dtjwpg_core_admin_toolbar_link' );
    delete_option( 'dtjwpg_donate_upsell' );
    add_option( 'dtjwpg_core_uninstall_remember', 1 );

    add_option( 'wp_guardian_firewall_enable', 1 );
    add_option( 'wp_guardian_firewall_long_requests', 1 );
    add_option( 'wp_guardian_firewall_xss', 1 );
    add_option( 'wp_guardian_firewall_log', 1 );

    add_option( 'wp_guardian_firewall_long_requests_length', 1000 );
    add_option( 'wp_guardian_firewall_log_size', 10000 );
    add_option( 'wp_guardian_firewall_redirect_page', '403' );

    // Hide Admin Backend
    add_option( 'dtjwpg_backend_token_option', '' );
    add_option( 'dtjwpg_backend_redirect_option', '' );
    // Database Backups
    delete_option( 'dtjwpg_backup_location_option' );
    delete_option( 'dtjwpg_backup_email_option' );
    // WordPress Options
    add_option( 'dtjwpg_wp_fileedit_option', 'off' );
    add_option( 'dtjwpg_wp_debug_option', 'off' );
    // Two Step Verification
    add_option( 'dtjwpg_verify_option', 'off' );

    delete_option( 'dtjwpg_version_option' );
    delete_option( 'dtjwpg_auto_updates_option' );
    delete_option( 'dtjwpg_update_core_option' );
    delete_option( 'dtjwpg_update_plugins_option' );
    delete_option( 'dtjwpg_update_themes_option' );
    delete_option( 'dtjwpg_update_l10n_option' );
    delete_option( 'dtjwpg_server_config_option' );
    delete_option( 'dtjwpg_wp_unfilter_option' );
    delete_option( 'dtjwpg_wp_wpssl_option' );
    delete_option( 'dtjwpg_database_version' );
    delete_option( 'dtjwpg_wp_restapi_option' );
    delete_option( 'dtjwpg_wp_xmlrpc_option' );
    delete_option( 'dtjwpg_lockout_logins_option' );
    delete_option( 'dtjwpg_lockout_email_option' );
    delete_option( 'dtjwpg_lockout_logins_threshold_option' );
    delete_option( 'dtjwpg_lockout_logins_time_option' );
    delete_option( 'dtjwpg_wp_headers_option' );
    delete_option( 'dtjwpg_wp_emojis_option' );
}

register_activation_hook( DTJWPG_URL, 'dtjwpg_plugin_install', 10, 0 );

/**
 * Run the plugin uninstaller on deactivation.
 *
 * @since 1.0
 * @return void
 */
function dtjwpg_plugin_uninstall() {
    // Check whether we need to keep the plugin data
    if ( 1 !== (int) get_option( 'dtjwpg_core_uninstall_remember' ) ) {
        // Get all the sites users
        $dtjwpg_users = get_users();

        // Loop through each user object
        foreach ( $dtjwpg_users as $dtjwpg_user ) {
            // Delete the Two Step user meta fields
            delete_user_meta( $dtjwpg_user->ID, 'dtjwpg_two_step_method' );
            delete_user_meta( $dtjwpg_user->ID, 'dtjwpg_tsv_expiry' );
        }

        // Core Plugin Settings
        delete_option( 'dtjwpg_core_admin_toolbar_link' );
        delete_option( 'dtjwpg_donate_upsell' );
        delete_option( 'dtjwpg_core_uninstall_remember' );
        // Hide Admin Backend
        delete_option( 'dtjwpg_backend_token_option' );
        delete_option( 'dtjwpg_backend_redirect_option' );
        // Database Backups
        delete_option( 'dtjwpg_backup_location_option' );
        delete_option( 'dtjwpg_backup_email_option' );
        // Lockout Management
        delete_option( 'dtjwpg_lockout_logins_option' );
        delete_option( 'dtjwpg_lockout_email_option' );
        delete_option( 'dtjwpg_lockout_logins_threshold_option' );
        delete_option( 'dtjwpg_lockout_logins_time_option' );
        // WordPress Options
        delete_option( 'dtjwpg_wp_unfilter_option' );
        delete_option( 'dtjwpg_wp_fileedit_option' );
        delete_option( 'dtjwpg_wp_xmlrpc_option' );
        delete_option( 'dtjwpg_wp_restapi_option' );
        delete_option( 'dtjwpg_wp_headers_option' );
        delete_option( 'dtjwpg_wp_emojis_option' );
        delete_option( 'dtjwpg_wp_debug_option' );
        delete_option( 'dtjwpg_wp_wpssl_option' );

        // Two Step Verification
        delete_option( 'dtjwpg_verify_option' );

        delete_option( 'dtjwpg_version_option' );
        delete_option( 'dtjwpg_auto_updates_option' );
        delete_option( 'dtjwpg_update_core_option' );
        delete_option( 'dtjwpg_update_plugins_option' );
        delete_option( 'dtjwpg_update_themes_option' );
        delete_option( 'dtjwpg_update_l10n_option' );
        delete_option( 'dtjwpg_server_config_option' );

        // Finally delete the plugin database version.
        delete_option( 'dtjwpg_database_version' );
    }
}

register_deactivation_hook( DTJWPG_URL, 'dtjwpg_plugin_uninstall', 10, 0 );
