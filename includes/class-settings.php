<?php
class Poll_Settings_Class {

    public function __construct() {
        $this->load_settings();
    }

    public function easy_poll_afo_options_save_settings() {
        if (isset($_POST['option']) and $_POST['option'] == "easy_poll_afo_options_save_settings") {
            if (!isset($_POST['easy_poll_afo_options_save_action_field']) || !wp_verify_nonce($_POST['easy_poll_afo_options_save_action_field'], 'easy_poll_afo_options_save_action')) {
                wp_die('Sorry, your nonce did not verify.');
            }

            if (isset($_POST['poll_result_after_it_ends'])) {
                update_option('poll_result_after_it_ends', sanitize_text_field($_POST['poll_result_after_it_ends']));
            } else {
                delete_option('poll_result_after_it_ends');
            }

            if (isset($_POST['poll_share_enable_facebook'])) {
                update_option('poll_share_enable_facebook', sanitize_text_field($_POST['poll_share_enable_facebook']));
            } else {
                delete_option('poll_share_enable_facebook');
            }

            if (isset($_POST['poll_share_enable_twitter'])) {
                update_option('poll_share_enable_twitter', sanitize_text_field($_POST['poll_share_enable_twitter']));
            } else {
                delete_option('poll_share_enable_twitter');
            }

            if (isset($_POST['poll_share_enable_google'])) {
                update_option('poll_share_enable_google', sanitize_text_field($_POST['poll_share_enable_google']));
            } else {
                delete_option('poll_share_enable_google');
            }

            if (isset($_POST['poll_share_enable_linkedin'])) {
                update_option('poll_share_enable_linkedin', sanitize_text_field($_POST['poll_share_enable_linkedin']));
            } else {
                delete_option('poll_share_enable_linkedin');
            }

            if (isset($_POST['poll_share_enable_email'])) {
                update_option('poll_share_enable_email', sanitize_text_field($_POST['poll_share_enable_email']));
            } else {
                delete_option('poll_share_enable_email');
            }

            $GLOBALS['msg'] = __('Data successfully updated.', 'wp-easy-poll-afo');
        }
    }

    public static function help_support() {
        include WPEPA_DIR_PATH . '/view/admin/help.php';
    }

    public function donate() {
        include WPEPA_DIR_PATH . '/view/admin/donate.php';
    }

    public function easy_poll_settings_afo_options() {
        global $wpdb;
        $poll_result_after_it_ends = get_option('poll_result_after_it_ends');
        $poll_share_enable_facebook = get_option('poll_share_enable_facebook');
        $poll_share_enable_twitter = get_option('poll_share_enable_twitter');
        $poll_share_enable_google = get_option('poll_share_enable_google');
        $poll_share_enable_linkedin = get_option('poll_share_enable_linkedin');
        $poll_share_enable_email = get_option('poll_share_enable_email');

        echo '<div class="wrap">';
        $this->show_message();
        $this->wp_easy_poll_pro_add();
        $this->help_support();
        include WPEPA_DIR_PATH . '/view/admin/settings.php';
        include WPEPA_DIR_PATH . '/view/admin/shortcodes.php';
        $this->donate();
        echo '</div>';
    }

    public function wp_easy_poll_pro_add() {
        include WPEPA_DIR_PATH . '/view/admin/pro-add.php';
    }

    public function show_message() {
        if (isset($GLOBALS['msg'])) {
            echo '<div class="updated"><p>' . $GLOBALS['msg'] . '</p></div>';
            $GLOBALS['msg'] = '';
        }
    }

    public function easy_polls_afo_options() {
        $pc = new Poll_Class();
        $pc->display_list();
    }

    public function easy_poll_afo_menu() {
        add_menu_page('Polls', 'Polls', 'activate_plugins', 'easy_polls', array($this, 'easy_polls_afo_options'), 'dashicons-chart-bar');
        add_submenu_page('easy_polls', 'Poll Settings', 'Poll Settings', 'activate_plugins', 'easy_poll_afo', array($this, 'easy_poll_settings_afo_options'));
    }

    public function load_settings() {
        add_action('admin_menu', array($this, 'easy_poll_afo_menu'));
        add_action('admin_init', array($this, 'easy_poll_afo_options_save_settings'));
    }

}
