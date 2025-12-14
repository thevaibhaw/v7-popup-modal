<?php

/**
 * V7 Popup Modal
 *
 * Lightweight plugin to display a configurable modal on the homepage.
 * Version: 1.0.0
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('V7_POPUP_MODAL_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-v7-popup-modal-activator.php
 */
function activate_v7_popup_modal()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-v7-popup-modal-activator.php';
    V7_Popup_Modal_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-v7-popup-modal-deactivator.php
 */
function deactivate_v7_popup_modal()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-v7-popup-modal-deactivator.php';
    V7_Popup_Modal_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_v7_popup_modal');
register_deactivation_hook(__FILE__, 'deactivate_v7_popup_modal');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-v7-popup-modal.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_v7_popup_modal()
{

    $plugin = new V7_Popup_Modal();
    $plugin->run();
}
run_v7_popup_modal();
