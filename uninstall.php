<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://vaibhawkumarparashar.in
 * @since      1.0.0
 *
 * @package    V7_Popup_Modal
 */

// If uninstall not called from WordPress, then exit.
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Delete plugin options on uninstall.
 */
delete_option('v7_popup_modal_options');
