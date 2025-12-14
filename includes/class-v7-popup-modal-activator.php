<?php

class V7_Popup_Modal_Activator
{
    public static function activate()
    {
        add_option('v7_popup_modal_options', array(
            'icon' => '',
            'title' => 'Welcome!',
            'content' => 'This is a customizable popup modal.',
            'word_limit' => 100,
            'timer_enabled' => false,
            'timer_duration' => 1,
        ));

        $files = array(
            'v7-popup-modal.php',
            'includes/class-v7-popup-modal.php',
            'includes/class-v7-popup-modal-loader.php',
            'includes/class-v7-popup-modal-integrity.php',
            'admin/class-v7-popup-modal-admin.php',
            'admin/js/v7-popup-modal-admin.js',
            'public/class-v7-popup-modal-public.php',
            'public/js/v7-popup-modal-public.js',
            'public/css/v7-popup-modal-public.css',
        );

        $hashes = array();
        $root = plugin_dir_path(dirname(__FILE__));
        foreach ($files as $f) {
            $p = $root . $f;
            if (file_exists($p)) {
                $hashes[$f] = sha1_file($p);
            }
        }
        add_option('v7_popup_modal_hashes', $hashes);
    }
}
