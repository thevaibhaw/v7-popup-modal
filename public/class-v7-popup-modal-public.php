<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://vaibhawkumarparashar.in
 * @since      1.0.0
 *
 * @package    V7_Popup_Modal
 * @subpackage V7_Popup_Modal/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    V7_Popup_Modal
 * @subpackage V7_Popup_Modal/public
 * @author     Vaibhaw Kumar <imvaibhaw@gmail.com>
 */
class V7_Popup_Modal_Public
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
     * @var      string    $version    The current version of the plugin.
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
     * Register the stylesheets for the public-facing side of the site.
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

        if (is_front_page()) {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/v7-popup-modal-public.css', array(), $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        if (is_front_page()) {
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/v7-popup-modal-public.js', array('jquery'), $this->version, false);

            $options = get_option('v7_popup_modal_options');
            $timer_enabled = isset($options['timer_enabled']) ? $options['timer_enabled'] : 0;
            $timer_duration = isset($options['timer_duration']) ? $options['timer_duration'] : 1;
            $timer_start = isset($options['timer_start']) ? intval($options['timer_start']) : 0;
            // compute remaining seconds based on saved start + duration (hours)
            $timer_remaining = 0;
            if ($timer_enabled && $timer_start > 0) {
                $end_time = $timer_start + (intval($timer_duration) * HOUR_IN_SECONDS);
                $now = time();
                $timer_remaining = max(0, $end_time - $now);
            }
            $background_color = isset($options['background_color']) ? $options['background_color'] : '#667eea';
            wp_localize_script($this->plugin_name, 'v7_popup_modal_vars', array(
                'timer_enabled' => $timer_enabled,
                'timer_duration' => $timer_duration,
                'timer_remaining' => $timer_remaining,
                'background_color' => $background_color,
            ));
        }
    }

    /**
     * Display the popup modal in the footer.
     *
     * @since    1.0.0
     */
    public function display_popup()
    {
        if (! is_front_page()) {
            return;
        }

        $options = get_option('v7_popup_modal_options');
        $icon = isset($options['icon']) ? $options['icon'] : '';
        $title = isset($options['title']) ? $options['title'] : '';
        $content = isset($options['content']) ? $options['content'] : '';
        $timer_enabled = isset($options['timer_enabled']) ? $options['timer_enabled'] : 0;
        $timer_duration = isset($options['timer_duration']) ? intval($options['timer_duration']) : 1;
        $timer_start = isset($options['timer_start']) ? intval($options['timer_start']) : 0;
        // If timer is enabled and has expired, don't render the modal
        if ($timer_enabled && $timer_start > 0) {
            $end_time = $timer_start + ($timer_duration * HOUR_IN_SECONDS);
            if (time() >= $end_time) {
                return; // timer expired; do not display modal
            }
        }

        $background_color = isset($options['background_color']) ? $options['background_color'] : '#667eea';
        $custom_css_scoped = isset($options['custom_css_scoped']) ? $options['custom_css_scoped'] : '';

?>
        <div id="v7-popup-modal" class="v7-popup-modal" role="dialog" aria-labelledby="v7-popup-title" aria-modal="true">
            <?php if ($custom_css_scoped) : ?>
                <style type="text/css">
                    <?php echo wp_strip_all_tags($custom_css_scoped); ?>
                </style>
            <?php endif; ?>
            <div class="v7-popup-modal-overlay"></div>
            <div class="v7-popup-modal-content" style="--v7-bg: <?php echo esc_attr($background_color); ?>;">
                <button class="v7-popup-modal-close" aria-label="Close popup">&times;</button>
                <?php if ($icon) : ?>
                    <img src="<?php echo esc_url($icon); ?>" alt="" class="v7-popup-icon" />
                <?php endif; ?>
                <h2 id="v7-popup-title" class="v7-popup-title"><?php echo esc_html($title); ?></h2>
                <div class="v7-popup-content"><?php echo wp_kses_post(wpautop($content)); ?></div>
                <?php if ($timer_enabled) : ?>
                    <div class="v7-popup-timer">Ends in <span id="v7-timer-countdown">01:00:00</span> Hours</div>
                <?php endif; ?>
            </div>
        </div>
<?php
    }
}
