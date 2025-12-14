<?php

/**
 * Integrity checker for V7 Popup Modal
 *
 * Detects file tampering by comparing known file hashes stored at activation.
 * If tampering is detected the plugin will deactivate itself and show an admin notice.
 *
 * @since 1.0.0
 */
class V7_Popup_Modal_Integrity
{

    /**
     * Files to check relative to plugin root.
     *
     * @var array
     */
    protected $files = array(
        'v7-popup-modal.php',
        'includes/class-v7-popup-modal.php',
        'includes/class-v7-popup-modal-integrity.php',
        'includes/class-v7-popup-modal-loader.php',
        'admin/class-v7-popup-modal-admin.php',
        'admin/js/v7-popup-modal-admin.js',
        'public/class-v7-popup-modal-public.php',
        'public/js/v7-popup-modal-public.js',
        'public/css/v7-popup-modal-public.css',
    );

    /**
     * Run integrity check and deactivate if mismatch.
     */
    public function check_integrity()
    {
        if (! is_admin()) {
            return;
        }

        $expected = get_option('v7_popup_modal_hashes', array());
        if (empty($expected) || ! is_array($expected)) {
            return; // nothing to compare
        }

        $plugin_root = plugin_dir_path(dirname(__FILE__));
        $mismatch = false;

        foreach ($this->files as $file) {
            $path = $plugin_root . $file;
            if (! file_exists($path)) {
                $mismatch = true;
                break;
            }
            $hash = sha1_file($path);
            if (! isset($expected[$file]) || $expected[$file] !== $hash) {
                $mismatch = true;
                break;
            }
        }

        if ($mismatch) {
            // mark tampered and deactivate
            update_option('v7_popup_modal_tampered', 1);
            $plugin_file = plugin_dir_path(dirname(__FILE__)) . 'v7-popup-modal.php';
            if (function_exists('deactivate_plugins')) {
                deactivate_plugins(plugin_basename($plugin_file));
            }
        }
    }

    /**
     * Admin notice when tampering detected.
     */
    public function admin_notice()
    {
        if (get_option('v7_popup_modal_tampered')) {
            echo '<div class="notice notice-error"><p><strong>V7 Popup Modal:</strong> Plugin files appear to have been modified. The plugin has been deactivated for security. Please restore original files or reinstall the plugin.</p></div>';
        }
    }
}
