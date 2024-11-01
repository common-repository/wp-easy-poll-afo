<?php

function wp_easy_poll_shortcode($atts) {
    global $post;
    extract(shortcode_atts(array(
        'id' => '',
    ), $atts));

    if (!$id) {
        return __('Poll is empty!', 'wp-easy-poll-afo');
    }

    ob_start();
    $pw = new Poll_Widget_Class;
    $pw->easyPoll(array('poll_id' => $id));
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
}

function wp_easy_polls_active_shortcode() {
    global $post, $wpdb;
    ob_start();
    $pw = new Poll_Widget_Class;
    $results = $wpdb->get_results($wpdb->prepare("SELECT p_id FROM " . $wpdb->prefix . "easy_poll_q WHERE p_status = %s ORDER BY p_added DESC", 'Active'));
    if ($results) {
        foreach ($results as $data) {
            echo '<div class="polls-all">';
            $pw->easyPoll(array('poll_id' => $data->p_id));
            echo '</div>';
        }
    }
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
}