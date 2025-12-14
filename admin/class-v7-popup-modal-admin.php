<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://vaibhawkumarparashar.in
 * @since      1.0.0
 *
 * @package    V7_Popup_Modal
 * @subpackage V7_Popup_Modal/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    V7_Popup_Modal
 * @subpackage V7_Popup_Modal/admin
 * @author     Vaibhaw Kumar <imvaibhaw@gmail.com>
 */
class V7_Popup_Modal_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in V7_Popup_Modal_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The V7_Popup_Modal_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/v7-popup-modal-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in V7_Popup_Modal_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The V7_Popup_Modal_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/v7-popup-modal-admin.js', array('jquery', 'wp-color-picker'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '-preview', plugin_dir_url(__FILE__) . 'js/v7-popup-modal-admin-preview.js', array('jquery'), $this->version, false);
    }

    /**
     * Add plugin admin menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu()
    {
        add_options_page(
            'V7 Popup Modal Settings',
            'V7 Popup Modal',
            'manage_options',
            'v7-popup-modal',
            array($this, 'display_plugin_setup_page')
        );
    }

    /**
     * Register settings.
     *
     * @since    1.0.0
     */
    public function register_settings()
    {
        register_setting('v7_popup_modal_options_group', 'v7_popup_modal_options', array($this, 'sanitize_options'));

        add_settings_section(
            'v7_popup_modal_general',
            'General Settings',
            array($this, 'general_section_callback'),
            'v7-popup-modal'
        );

        add_settings_field(
            'icon',
            'Icon',
            array($this, 'icon_callback'),
            'v7-popup-modal',
            'v7_popup_modal_general'
        );

        add_settings_field(
            'title',
            'Popup Title',
            array($this, 'title_callback'),
            'v7-popup-modal',
            'v7_popup_modal_general'
        );

        add_settings_field(
            'content',
            'Popup Content',
            array($this, 'content_callback'),
            'v7-popup-modal',
            'v7_popup_modal_general'
        );

        add_settings_field(
            'word_limit',
            'Word Limit',
            array($this, 'word_limit_callback'),
            'v7-popup-modal',
            'v7_popup_modal_general'
        );

        add_settings_field(
            'timer_enabled',
            'Enable Timer',
            array($this, 'timer_enabled_callback'),
            'v7-popup-modal',
            'v7_popup_modal_general'
        );

        add_settings_field(
            'timer_duration',
            'Timer Duration (hours)',
            array($this, 'timer_duration_callback'),
            'v7-popup-modal',
            'v7_popup_modal_general'
        );
        add_settings_field(
            'background_color',
            'Background Color',
            array($this, 'background_color_callback'),
            'v7-popup-modal',
            'v7_popup_modal_general'
        );
        add_settings_field(
            'custom_css',
            'Custom Modal CSS',
            array($this, 'custom_css_callback'),
            'v7-popup-modal',
            'v7_popup_modal_general'
        );
    }

    /**
     * Sanitize options.
     *
     * @since    1.0.0
     * @param    array    $input    The input options.
     * @return   array              The sanitized options.
     */
    public function sanitize_options($input)
    {
        $sanitized = array();

        $sanitized['icon'] = esc_url_raw($input['icon']);
        $sanitized['title'] = sanitize_text_field($input['title']);
        $sanitized['content'] = sanitize_textarea_field($input['content']);
        $sanitized['word_limit'] = absint($input['word_limit']);
        $sanitized['timer_enabled'] = isset($input['timer_enabled']) ? 1 : 0;
        $sanitized['timer_duration'] = absint($input['timer_duration']);
        // Manage timer start: if timer enabled and either newly enabled or duration changed, set start timestamp
        $existing = get_option('v7_popup_modal_options', array());
        $prev_enabled = isset($existing['timer_enabled']) ? $existing['timer_enabled'] : 0;
        $prev_duration = isset($existing['timer_duration']) ? intval($existing['timer_duration']) : 0;
        if ($sanitized['timer_enabled']) {
            if (! $prev_enabled || $prev_duration !== $sanitized['timer_duration']) {
                // (re)start timer now
                $sanitized['timer_start'] = time();
            } else {
                // keep previous start if present
                $sanitized['timer_start'] = isset($existing['timer_start']) ? intval($existing['timer_start']) : time();
            }
        } else {
            // clear start if timer disabled
            $sanitized['timer_start'] = 0;
        }
        // Background color
        $sanitized['background_color'] = isset($input['background_color']) ? sanitize_hex_color($input['background_color']) : '';
        // Custom CSS: allow CSS but strip tags; scope it to modal when saving
        $raw_css = isset($input['custom_css']) ? $input['custom_css'] : '';
        $raw_css = wp_strip_all_tags($raw_css);
        $sanitized['custom_css'] = $raw_css;
        $sanitized['custom_css_scoped'] = $this->scope_css_to_modal($raw_css);


        // Enforce word limit
        $words = str_word_count($sanitized['content']);
        if ($words > $sanitized['word_limit']) {
            $sanitized['content'] = wp_trim_words($sanitized['content'], $sanitized['word_limit']);
        }

        return $sanitized;
    }

    /**
     * General section callback.
     *
     * @since    1.0.0
     */
    public function general_section_callback()
    {
        echo '<p>Configure the popup modal settings.</p>';
    }

    /**
     * Icon callback.
     *
     * @since    1.0.0
     */
    public function icon_callback()
    {
        $options = get_option('v7_popup_modal_options');
        $icon = isset($options['icon']) ? $options['icon'] : '';
?>
        <input type="text" id="v7_icon_url" name="v7_popup_modal_options[icon]" value="<?php echo esc_attr($icon); ?>" class="regular-text" readonly />
        <input type="button" id="v7_upload_icon_button" class="button" value="Upload Icon" />
        <input type="button" id="v7_remove_icon_button" class="button" value="Remove Icon" style="display: <?php echo $icon ? 'inline-block' : 'none'; ?>;" />
        <?php if ($icon) : ?>
            <br><img src="<?php echo esc_url($icon); ?>" style="max-width: 100px; max-height: 100px; margin-top: 10px;" />
        <?php endif; ?>
    <?php
    }

    /**
     * Title callback.
     *
     * @since    1.0.0
     */
    public function title_callback()
    {
        $options = get_option('v7_popup_modal_options');
        $title = isset($options['title']) ? $options['title'] : '';
        echo '<input type="text" name="v7_popup_modal_options[title]" value="' . esc_attr($title) . '" class="regular-text" />';
    }

    /**
     * Content callback.
     *
     * @since    1.0.0
     */
    public function content_callback()
    {
        $options = get_option('v7_popup_modal_options');
        $content = isset($options['content']) ? $options['content'] : '';
        echo '<textarea name="v7_popup_modal_options[content]" rows="5" cols="50" class="large-text">' . esc_textarea($content) . '</textarea>';
    }

    /**
     * Word limit callback.
     *
     * @since    1.0.0
     */
    public function word_limit_callback()
    {
        $options = get_option('v7_popup_modal_options');
        $word_limit = isset($options['word_limit']) ? $options['word_limit'] : 100;
        echo '<input type="number" name="v7_popup_modal_options[word_limit]" value="' . esc_attr($word_limit) . '" min="1" />';
    }

    /**
     * Timer enabled callback.
     *
     * @since    1.0.0
     */
    public function timer_enabled_callback()
    {
        $options = get_option('v7_popup_modal_options');
        $timer_enabled = isset($options['timer_enabled']) ? $options['timer_enabled'] : 0;
        echo '<input type="checkbox" name="v7_popup_modal_options[timer_enabled]" value="1" ' . checked(1, $timer_enabled, false) . ' />';
    }

    /**
     * Timer duration callback.
     *
     * @since    1.0.0
     */
    public function timer_duration_callback()
    {
        $options = get_option('v7_popup_modal_options');
        $timer_duration = isset($options['timer_duration']) ? $options['timer_duration'] : 1;
        echo '<input type="number" name="v7_popup_modal_options[timer_duration]" value="' . esc_attr($timer_duration) . '" min="1" />';
    }

    /**
     * Background color callback.
     *
     * @since    1.0.0
     */
    public function background_color_callback()
    {
        $options = get_option('v7_popup_modal_options');
        $bg = isset($options['background_color']) ? $options['background_color'] : '#667eea';
        echo '<input type="text" id="v7_bg_color" name="v7_popup_modal_options[background_color]" value="' . esc_attr($bg) . '" class="v7-color-field" />';
    }

    /**
     * Custom CSS callback.
     */
    public function custom_css_callback()
    {
        $options = get_option('v7_popup_modal_options');
        $css = isset($options['custom_css']) ? $options['custom_css'] : '';
        echo '<textarea name="v7_popup_modal_options[custom_css]" rows="8" cols="50" class="large-text code" placeholder="/* CSS will be scoped to the modal (#v7-popup-modal) */">' . esc_textarea($css) . '</textarea>';
        echo '<p class="v7-field-note">Enter CSS rules to style only the popup modal. Rules will be automatically scoped to the modal so they won\'t affect other site elements.</p>';
    }

    /**
     * Scope user CSS so selectors are prefixed with the modal id.
     * Basic processor: handles @media blocks and prefixes selectors.
     */
    private function scope_css_to_modal($css)
    {
        $scope = '#v7-popup-modal';
        if (trim($css) === '') {
            return '';
        }

        // Recursive scoping to handle @media blocks
        $process = function ($css) use (&$process, $scope) {
            $output = '';
            $offset = 0;
            while (preg_match('/@media[^{]*\{/i', $css, $m, PREG_OFFSET_CAPTURE, $offset)) {
                $pos = $m[0][1];
                $before = substr($css, $offset, $pos - $offset);
                $output .= $this->prefix_selectors($before, $scope);
                $start = $pos + strlen($m[0][0]);
                $depth = 1;
                $i = $start;
                $len = strlen($css);
                while ($i < $len && $depth > 0) {
                    if ($css[$i] === '{') $depth++;
                    elseif ($css[$i] === '}') $depth--;
                    $i++;
                }
                $inner = substr($css, $start, $i - $start - 1);
                $media_header = substr($css, $pos, strlen($m[0][0]));
                $output .= $media_header . $process($inner) . '}';
                $offset = $i;
            }
            $output .= $this->prefix_selectors(substr($css, $offset), $scope);
            return $output;
        };

        return $process($css);
    }

    private function prefix_selectors($css_fragment, $scope)
    {
        return preg_replace_callback('/([^{}]+)\{([^}]*)\}/s', function ($matches) use ($scope) {
            $selectors = trim($matches[1]);
            $rules = $matches[2];
            // If it's an at-rule (like @keyframes), leave unchanged
            if (strpos($selectors, '@') === 0) {
                return $matches[0];
            }
            $parts = array_map('trim', explode(',', $selectors));
            $new_parts = array();
            foreach ($parts as $sel) {
                if ($sel === '') continue;
                // If selector already contains the scope, keep it
                if (strpos($sel, $scope) !== false) {
                    $new_parts[] = $sel;
                } else {
                    $new_parts[] = $scope . ' ' . $sel;
                }
            }
            if (empty($new_parts)) return '';
            return implode(', ', $new_parts) . ' {' . $rules . '}';
        }, $css_fragment);
    }

    /**
     * Display plugin setup page.
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page()
    {
    ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <?php
            $options = get_option('v7_popup_modal_options');
            $title = isset($options['title']) ? $options['title'] : 'Welcome!';
            $content = isset($options['content']) ? $options['content'] : 'This is a customizable popup modal.';
            $timer_enabled = isset($options['timer_enabled']) ? $options['timer_enabled'] : 0;
            $timer_duration = isset($options['timer_duration']) ? intval($options['timer_duration']) : 1;
            $timer_start = isset($options['timer_start']) ? intval($options['timer_start']) : 0;
            $timer_remaining = 0;
            $timer_start_label = '';
            $timer_end_label = '';
            if ($timer_enabled && $timer_start > 0) {
                $end_time = $timer_start + ($timer_duration * HOUR_IN_SECONDS);
                $timer_remaining = max(0, $end_time - time());
                // Format start/end using WordPress/site timezone in 12-hour format with AM/PM, e.g. "Monday, 4:01:20 AM"
                if (function_exists('wp_date')) {
                    $timer_start_label = wp_date('l, g:i:s A', $timer_start);
                    $timer_end_label   = wp_date('l, g:i:s A', $end_time);
                } else {
                    $timer_start_label = date('l, g:i:s A', $timer_start);
                    $timer_end_label   = date('l, g:i:s A', $end_time);
                }
            }
            ?>
            <form method="post" action="options.php">
                <div class="v7-v7-settings-wrap">
                    <div class="v7-settings-main">
                        <?php
                        settings_fields('v7_popup_modal_options_group');
                        do_settings_sections('v7-popup-modal');
                        ?>
                        <p class="v7-field-note">After enabling the timer or changing the duration, the timer will (re)start from the moment you save.</p>
                        <?php submit_button(); ?>
                    </div>
                    <aside class="v7-settings-sidebar">
                        <div class="v7-preview-box">
                            <div class="v7-preview-title"><?php echo esc_html($title); ?></div>
                            <div class="v7-preview-content"><?php echo wp_kses_post(wpautop($content)); ?></div>
                            <?php if ($timer_enabled) : ?>
                                <div class="v7-preview-timer">Remaining: <span id="v7-admin-timer"><?php echo esc_html(gmdate('H:i:s', $timer_remaining)); ?></span></div>
                                <div class="v7-field-note">Duration: <?php echo esc_html($timer_duration); ?> hour(s)</div>
                                <div class="v7-field-note">Starts: <?php echo esc_html($timer_start_label); ?></div>
                                <div class="v7-field-note">Expires: <?php echo esc_html($timer_end_label); ?></div>
                            <?php else : ?>
                                <div class="v7-field-note">Timer is disabled.</div>
                            <?php endif; ?>
                        </div>
                    </aside>
                </div>
            </form>
        </div>
<?php
    }
}
