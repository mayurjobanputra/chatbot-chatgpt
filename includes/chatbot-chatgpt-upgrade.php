<?php
/**
 * Chatbot ChatGPT for WordPress - Upgrade the chatbot-chatgpt plugin.
 *
 * This file contains the code for upgrading the plugin.
 * It should run with the plugin is activated, deactivated, or updated.
 *
 * @package chatbot-chatgpt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) )
die;

// FIXME - THIS IS NOT WORKING AS EXPECTED - Ver 1.6.7

// If the plugin is updated, run the upgrade function.
function chatbot_chatgpt_upgrade_completed($upgrader_object, $options) {

    // DIAG - Log the upgrade.
    // chatbot_chatgpt_back_trace( 'NOTICE', 'Plugin upgrade stated');
    // chatbot_chatgpt_back_trace( 'NOTICE', '$upgrader_object: ' . print_r( $upgrader_object, true ));
    // chatbot_chatgpt_back_trace( 'NOTICE', ['message' => '$options: ', 'options' => $options]);

    // Check if the plugin was updated
    if (is_array($options) && isset($options['action']) && isset($options['type']) && $options['action'] == 'update' && $options['type'] == 'plugin' ) {
        foreach($options['plugins'] as $plugin) {
            if ($plugin == plugin_basename(__FILE__)) {
                // DIAG - Log the action.
                // chatbot_chatgpt_back_trace( 'SUCCESS', 'Plugin upgraded.' );
                // The plugin was updated.
                // Now run the upgrade function.
                chatbot_chatgpt_upgrade();
            }
        }
    }

    // DIAG - Log the upgrade.
    // chatbot_chatgpt_back_trace( 'SUCCESS', 'Plugin upgrade completed');

    return;

}
// FIXME - THIS IS NOT WORKING AS EXPECTED - Ver 1.6.7
add_action('upgrader_process_complete', 'chatbot_chatgpt_upgrade_completed', 10, 2);

// If the plugin is activated or deactivated, run the upgrade function.
function chatbot_chatgpt_upgrade_activation_deactivation($upgrader_object, $options) {

    // DIAG - Log the upgrade.
    // chatbot_chatgpt_back_trace( 'NOTICE', 'Plugin activation/deactivation started');
    // chatbot_chatgpt_back_trace( 'NOTICE', "$upgrader_object: " . print_r( $upgrader_object, true ));
    // chatbot_chatgpt_back_trace( 'NOTICE', ['message' => '$options: ', 'options' => $options]);

    // Check if our plugin was activated
    if (is_array($options) && isset($options['action']) && isset($options['type']) && $options['action'] == 'activate' && $options['type'] == 'plugin' ) {
        foreach($options['plugins'] as $plugin) {
            if ($plugin == plugin_basename(__FILE__)) {
                // DIAG - Log the action.
                // chatbot_chatgpt_back_trace( 'NOTICE', 'Plugin activation');
                // The plugin was activated.
                // Run the upgrade function.
                chatbot_chatgpt_upgrade();
            }
        }
    }

    // Check if our plugin was deactivated
    if (is_array($options) && isset($options['action']) && isset($options['type']) && $options['action'] == 'deactivate' && $options['type'] == 'plugin' ) {
        foreach($options['plugins'] as $plugin) {
            if ($plugin == plugin_basename(__FILE__)) {
                // DIAG - Log the action.
                // chatbot_chatgpt_back_trace( 'NOTICE', 'Plugin deactivation');
                // The plugin was deactivated.
                // TODO - Add code to run when plugin is deactivated.
            }
        }
    }
    
    // DIAG - Log the upgrade.
    // chatbot_chatgpt_back_trace( 'NOTICE', 'COMPLETED: chatbot_chatgpt_upgrade_activation_deactinvation');

    return;

}
// FIXME - THIS IS NOT WORKING AS EXPECTED - Ver 1.6.7
register_activation_hook(__FILE__, 'chatbot_chatgpt_upgrade_activation_deactivation');

// If updating the plugin, run the upgrade function.
function chatbot_chatgpt_upgrade() {

    // DIAG - Log the upgrade.
    // chatbot_chatgpt_back_trace( 'NOTICE', 'Plugin upgrade started');

    // Get the current version of the plugin.
    $version = get_option( 'chatbot_chatgpt_plugin_version' );

    // If the plugin is not installed, set the version to 0.
    if ( ! $version ) {
        $version = '0';
    }

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    // If the plugin is installed but not activated, set the version to 0.
    if ( $version && ! is_plugin_active( plugins_url('', __FILE__ ) . '/chatbot-chatgpt.php' ) ) {
        $version = '0';
    }

    // If the plugin is installed and activated, run the upgrade function.
    if ( $version && is_plugin_active( 'chatbot-chatgpt/chatbot-chatgpt.php' ) ) {
        // If the plugin is version 1.0.0 or older, run the upgrade function.
        if ( version_compare( $version, '1.6.7', '<' ) ) {
            chatbot_chatgpt_upgrade_167();
            // DIAG - Log the currrent plugin version.
            // chatbot_chatgpt_back_trace( 'NOTICE', 'Current plugin version is ' . $version );
        }
    }

    // DIAG - Log the upgrade.
    // chatbot_chatgpt_back_trace('SUCCESS', 'Upgrade completed.' );

    return;
}


// Udgrade the plugin to version 1.6.7.
function chatbot_chatgpt_upgrade_167() {

    // DIAG - Log the upgrade.
    // chatbot_chatgpt_back_trace( 'NOTICE', 'Upgrade started for version 1.6.7');

    // Determine if option chatbot_chatgpt_crawler_status is in the options table.
    // If it is then remove it.
    if ( get_option( 'chatbot_chatgpt_crawler_status' ) ) {
        delete_option( 'chatbot_chatgpt_crawler_status' );
    }

    // Determine if option chatbot_chatgpt_diagnostics is in the options table.
    // If it is and the value is null or empty or blank then set it to No.
    if ( get_option( 'chatbot_chatgpt_diagnostics' ) ) {
        $diagnostics = get_option( 'chatbot_chatgpt_diagnostics' );
        if ( ! $diagnostics ) {
            update_option( 'chatbot_chatgpt_diagnostics', 'No' );
        }
        if ( $diagnostics == '' ) {
            update_option( 'chatbot_chatgpt_diagnostics', 'No' );
        }
        if ( $diagnostics == ' ' ) {
            update_option( 'chatbot_chatgpt_diagnostics', 'No' );
        }
    }

    // Determine if option chatgpt_plugin_version is in the options table.
    // If it is then remove it and add option chatbot_chatgpt_plugin_version only chatbot_chatgpt_plugin_version isn't in the options table.
    if ( get_option( 'chatgpt_plugin_version' ) ) {
        delete_option( 'chatgpt_plugin_version' );
        if ( ! get_option( 'chatbot_chatgpt_plugin_version' ) ) {
            add_option( 'chatbot_chatgpt_plugin_version', '1.6.7' );
        }
    }

    // DIAG - Log the upgrade.
    // chatbot_chatgpt_back_trace( 'SUCCESS', 'Upgrade completed for version 1.6.7');

    return;

}