<?php
/*
Plugin Name: WP Easy Poll
Plugin URI: https://wordpress.org/plugins/wp-easy-poll-afo/
Description: This is an easy poll plugin. Polls can be created from admin panel and displayed as widget in frontend. Users can submit vote and view poll results from frontend.
Version: 2.2.9
Text Domain: wp-easy-poll-afo
Domain Path: /languages
Author: aviplugins.com
Author URI: https://www.aviplugins.com/
*/

/*
  |||||
<(`0_0`)>
()(afo)()
  ()-()
 */

define('WPEPA_DIR_NAME', 'wp-easy-poll-afo');
define('WPEPA_DIR_PATH', dirname(__FILE__));

function plug_load_wp_easy_poll_afo() {

    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    if (is_plugin_active('wp-easy-poll-pro/wp_easy_poll.php')) {
        wp_die('It seems you have <strong>WP Easy Poll PRO</strong> plugin activated. Please deactivate that to continue.');
        exit;
    }

    include_once WPEPA_DIR_PATH . '/includes/class-settings.php';
    include_once WPEPA_DIR_PATH . '/includes/class-scripts.php';
    include_once WPEPA_DIR_PATH . '/includes/class-general.php';
    include_once WPEPA_DIR_PATH . '/includes/class-paginate.php';
    include_once WPEPA_DIR_PATH . '/includes/class-message.php';
    include_once WPEPA_DIR_PATH . '/includes/class-poll.php';
    include_once WPEPA_DIR_PATH . '/includes/class-poll-widget.php';

    include_once WPEPA_DIR_PATH . '/poll-data.php';
    include_once WPEPA_DIR_PATH . '/poll-widget.php';
    include_once WPEPA_DIR_PATH . '/shortcodes.php';
    include_once WPEPA_DIR_PATH . '/functions.php';

    new Poll_Settings_Class;
    new Poll_Scripts;
}

class WP_Easy_Poll_Load {
    function __construct() {
        plug_load_wp_easy_poll_afo();
    }
}
new WP_Easy_Poll_Load;

add_action('admin_init', 'process_poll_data');

add_action('widgets_init', function () {register_widget('Poll_Wid');});

add_action('init', 'poll_validate');

add_action('plugins_loaded', 'wp_easy_poll_text_domain');

add_shortcode('easypoll', 'wp_easy_poll_shortcode');
add_shortcode('easypolllist_all', 'wp_easy_polls_active_shortcode');

class WP_Easy_Poll_Init {
    static function install() {
        global $wpdb;
        $create_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "easy_poll_q` (
		  `p_id` int(11) NOT NULL AUTO_INCREMENT,
		  `p_ques` varchar(255) NOT NULL,
		  `p_author` int(11) NOT NULL,
		  `p_start` datetime NOT NULL,
		  `p_end` datetime NOT NULL,
		  `p_added` datetime NOT NULL,
		  `p_status` enum('Active','Inactive','Deleted') NOT NULL,
		  PRIMARY KEY (`p_id`)
		)";
        $wpdb->query($create_table);
        $create_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "easy_poll_a` (
		  `a_id` int(11) NOT NULL AUTO_INCREMENT,
		  `p_id` int(11) NOT NULL,
		  `a_ans` varchar(255) NOT NULL,
		  `a_order` int(11) NOT NULL,
		  PRIMARY KEY (`a_id`)
		)";
        $wpdb->query($create_table);
        $create_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "easy_poll_votes` (
		  `v_id` int(11) NOT NULL AUTO_INCREMENT,
		  `p_id` int(11) NOT NULL,
		  `a_id` int(11) NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `user_ip` varchar(50) NOT NULL,
		  `v_added` datetime NOT NULL,
		  PRIMARY KEY (`v_id`)
		)";
        $wpdb->query($create_table);
    }
    static function uninstall() {}
}
register_activation_hook(__FILE__, array('WP_Easy_Poll_Init', 'install'));

register_deactivation_hook(__FILE__, array('WP_Easy_Poll_Init', 'uninstall'));
