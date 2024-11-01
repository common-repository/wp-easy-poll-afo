<?php
class Poll_Scripts {

    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'front_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    }

    public function admin_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-timepicker-addon', plugins_url(WPEPA_DIR_NAME . '/assets/js/jquery-ui-timepicker-addon.js'), array('jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-slider'));
        wp_enqueue_script('easy-poll-js', plugins_url(WPEPA_DIR_NAME . '/assets/js/easy-poll-js.js'));
        wp_enqueue_style('jquery-ui', plugins_url(WPEPA_DIR_NAME . '/assets/css/jquery-ui.css'));
        wp_enqueue_style('style_easy_poll_admin', plugins_url(WPEPA_DIR_NAME . '/assets/css/style_easy_poll_admin.css'));

        wp_enqueue_script('ap.cookie', plugins_url(WPEPA_DIR_NAME . '/assets/js/ap.cookie.js'));
        wp_enqueue_script('ap-tabs', plugins_url(WPEPA_DIR_NAME . '/assets/js/ap-tabs.js'));

    }

    public function front_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-timepicker-addon', plugins_url(WPEPA_DIR_NAME . '/assets/js/jquery-ui-timepicker-addon.js'), array('jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-slider'));
        wp_enqueue_script('easy-poll-js', plugins_url(WPEPA_DIR_NAME . '/assets/js/easy-poll-js.js'));
        wp_enqueue_style('jquery-ui', plugins_url(WPEPA_DIR_NAME . '/assets/css/jquery-ui.css'));
        wp_enqueue_style('style_easy_poll', plugins_url(WPEPA_DIR_NAME . '/assets/css/style_easy_poll.css'));
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    }

}
