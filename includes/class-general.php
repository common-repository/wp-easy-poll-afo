<?php

class General_Poll_Class {

    public $poll_bar_height = '';
    public $poll_bar_color = '';
    public $poll_background_color = '';

    public function __construct() {
        $this->poll_bar_height = '12'; // in px
        $this->poll_bar_color = '#2697D3'; // in hex
        $this->poll_background_color = '#f8f8f8'; // in hex
    }

    public function is_poll_started($p_id = '') {
        if ($p_id == '') {
            return false;
        }
        global $wpdb;
        $pc = new Poll_Class;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $pc->table . " where p_id = %d and p_start <= now()", $p_id);
        $result = $wpdb->get_row($query, ARRAY_A);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function is_poll_closed($p_id = '') {
        if ($p_id == '') {
            return false;
        }
        global $wpdb;
        $pc = new Poll_Class;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $pc->table . " where p_id = %d and p_end <= now()", $p_id);
        $result = $wpdb->get_row($query, ARRAY_A);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function is_poll_active($p_id = '') {
        if ($p_id == '') {
            return false;
        }
        global $wpdb;
        $pc = new Poll_Class;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $pc->table . " where p_id = %d and p_start <= now() and p_end >= now()", $p_id);
        $result = $wpdb->get_row($query, ARRAY_A);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function poll_status_message($p_id = '') {
        if ($p_id == '') {
            return array('status' => false, 'msg' => __('Poll is empty!', 'wp-easy-poll-afo'), 'status_msg' => 'poll_not_found');
        }
        global $wpdb;
        $pc = new Poll_Class;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $pc->table . " WHERE p_id = %d AND p_start <= NOW() AND p_end >= NOW()", $p_id);
        $result = $wpdb->get_row($query, ARRAY_A);
        $data = $pc->get_single_row_data($p_id);
        if ($result) {
            return array('status' => true, 'status_msg' => 'active');
        } else {
            if (!$this->is_poll_started($p_id)) {
                return array('status' => false, 'msg' => __('Voting will start on', 'wp-easy-poll-afo') . ' ' . date('jS F, Y \a\t g:i a', strtotime($data['p_start'])), 'status_msg' => 'voting_not_started');
            }
            if ($this->is_poll_closed($p_id)) {
                return array('status' => false, 'msg' => __('Voting is over', 'wp-easy-poll-afo'), 'status_msg' => 'voting_over');
            }
        }
    }

    public function poll_status_admin($p_id = '') {
        if ($p_id == '') {
            return __('Poll is empty', 'wp-easy-poll-afo');
        }
        global $wpdb;
        $pc = new Poll_Class;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $pc->table . " where p_id = %d and p_start <= now() and p_end >= now()", $p_id);
        $result = $wpdb->get_row($query, ARRAY_A);
        $data = $pc->get_single_row_data($p_id);
        if ($result) {
            return __('Voting started', 'wp-easy-poll-afo');
        } else {
            if (!$this->is_poll_started($p_id)) {
                return __('Voting not started', 'wp-easy-poll-afo');
            }
            if ($this->is_poll_closed($p_id)) {
                return __('Voting is over', 'wp-easy-poll-afo');
            }
        }
    }

    public function get_user_name_or_email($user_id = '') {
        if ($user_id == '') {
            return;
        }
        $name_or_email = '';
        $user_info = get_userdata($user_id);
        if (is_object($user_info)) {
            $name_or_email = ($user_info->user_email == '' ? $user_info->display_name : $user_info->user_email);
        }

        return $name_or_email == '' ? __('Visitor', 'wp-easy-poll-afo') : $name_or_email;
    }

    public function get_vote_result_link($poll_id = '') {
        if ($poll_id == '') {
            return;
        } else {
            return '<input type="button" class="poll_button" onclick="LoadPollResult(' . $poll_id . ')" value="' . __('View Result', 'wp-easy-poll-afo') . '">';
        }
    }

    public function get_polls_selected($sel_id = '') {
        global $wpdb;
        $pc = new Poll_Class;
        $ret = '';
        $query = "SELECT * FROM " . $wpdb->prefix . $pc->table . " where p_status='Active' order by p_added desc";
        $results = $wpdb->get_results($query, ARRAY_A);

        foreach ($results as $key => $value) {
            if ($sel_id == $value['p_id']) {
                $ret .= '<option value="' . $value['p_id'] . '" selected="selected">' . stripslashes($value['p_ques']) . '</option>';
            } else {
                $ret .= '<option value="' . $value['p_id'] . '">' . stripslashes($value['p_ques']) . '</option>';
            }
        }
        return $ret;
    }

    public function get_p_id_from_a_id($a_id = '') {
        if ($a_id == '') {
            return false;
        }
        global $wpdb;
        $pc = new Poll_Class;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $pc->table2 . " where a_id = %d", $a_id);
        $result = $wpdb->get_row($query, ARRAY_A);

        if ($result) {
            return $result['p_id'];
        } else {
            return false;
        }
    }

    public function check_if_user_has_voted($user_id = '', $user_ip = '', $p_id = '') {
        global $wpdb;
        $pc = new Poll_Class;

        if ($p_id == '') {
            return false;
        }
        if ($user_id != '' and $user_ip == '') {
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $pc->table3 . " where p_id = %d AND user_id = %d", $p_id, $user_id);
            $result = $wpdb->get_row($query, ARRAY_A);
            if ($result) {
                return false;
            } else {
                return true;
            }
        } elseif ($user_id == '' and $user_ip != '') {
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $pc->table3 . " where p_id = %d AND user_id = %d AND user_ip = %s", $p_id, '', $user_ip);
            $result = $wpdb->get_row($query, ARRAY_A);
            if ($result) {
                return false;
            } else {
                return true;
            }
        } elseif ($user_id != '' and $user_ip != '') {
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $pc->table3 . " where p_id = %d AND user_id = %d AND user_ip = %s", $p_id, $user_id, $user_ip);
            $result = $wpdb->get_row($query, ARRAY_A);
            if ($result) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function save_user_vote($data = array(), $data_format = array()) {
        global $wpdb;
        $pc = new Poll_Class;

        if (empty($data) or !is_array($data)) {
            return array('res' => 'error', 'msg' => 'Data is empty!');
        }

        // check if the user is logged in or not 
        if (is_user_logged_in()) {
            // if logged in then check if this user has voted in this poll
            $user_id = get_current_user_id();
            $res = $this->check_if_user_has_voted($user_id, '', $data['p_id']);
            if ($res) {
                $wpdb->insert($wpdb->prefix . $pc->table3, $data, $data_format);
                return array('res' => 'success', 'msg' => __('Your vote is successfully added.', 'wp-easy-poll-afo'));
            } else {
                return array('res' => 'error', 'msg' => __('You have already voted for this poll.', 'wp-easy-poll-afo'));
            }
        }

        // if user is not logged in
        if (!is_user_logged_in()) {
            // if logged in then check if this user has voted in this poll
            $res = $this->check_if_user_has_voted('', $data['user_ip'], $data['p_id']);
            if ($res) {
                $wpdb->insert($wpdb->prefix . $pc->table3, $data, $data_format);
                return array('res' => 'success', 'msg' => __('Your vote is successfully added.', 'wp-easy-poll-afo'));
            } else {
                return array('res' => 'error', 'msg' => __('You have already voted for this poll.', 'wp-easy-poll-afo'));
            }
        }
        return array('res' => 'error', 'msg' => __('Please try again.', 'wp-easy-poll-afo'));
    }

    public function voting_result($p_id = '') {
        global $wpdb;
        $pc = new Poll_Class;
        $ret = '';
        if ($p_id == '') {
            $ret .= __('Poll is empty!', 'wp-easy-poll-afo');
            return $ret;
        }
        $poll_result_after_it_ends = get_option('poll_result_after_it_ends');
        if ($poll_result_after_it_ends == 'Yes' and !$this->is_poll_closed($p_id)) {
            $ret .= __('Check result after poll ends.', 'wp-easy-poll-afo');
            return $ret;
        }
        // get poll options 
        $data1 = $pc->get_poll_answers_data($p_id);
        if (is_array($data1)) {
            $ret .= '<ul class="poll_answers">';
            foreach ($data1 as $key => $value) {
                $ret .= '<li>';
                $ret .= stripslashes($value['a_ans']);
                $ret .= $this->answer_bar($p_id, $value['a_id']);
                $ret .= '</li>';
            }
            $ret .= '</ul>';
        }
        return $ret;
    }

    public function answer_bar($p_id = '', $a_id = '') {
        if ($p_id == '' or $a_id == '') {
            return;
        }
        $total_votes = $this->get_total_votes($p_id);
        $total_votes_by_ans = $this->get_total_votes_by_a_id($a_id);

        if ($total_votes) {
            $ans_per = ((100 / $total_votes) * $total_votes_by_ans);
            $ans_per = number_format($ans_per, 2);
        } else {
            $ans_per = 0;
        }

        $bar = '<div class="ans_val">(' . $total_votes_by_ans . '/' . $total_votes . ')</div>';
        $bar .= '<div style="height:' . $this->poll_bar_height . 'px; width:100%; background-color:' . $this->poll_background_color . ';"><div style="background-color:' . $this->poll_bar_color . ';height:' . $this->poll_bar_height . 'px; width:' . $ans_per . '%;"></div></div>';
        return $bar;
    }

    public function get_total_votes_by_a_id($a_id = '') {
        if ($a_id == '') {
            return;
        }
        global $wpdb;
        $pc = new Poll_Class;
        $query = $wpdb->prepare("SELECT count(*) as tot FROM " . $wpdb->prefix . $pc->table3 . " where a_id = %d", $a_id);
        $result = $wpdb->get_row($query, ARRAY_A);
        if ($result) {
            return $result['tot'];
        } else {
            return 0;
        }
    }

    public function get_total_votes($p_id = '') {
        if ($p_id == '') {
            return;
        }
        global $wpdb;
        $pc = new Poll_Class;
        $query = $wpdb->prepare("SELECT count(*) as tot FROM " . $wpdb->prefix . $pc->table3 . " where p_id = %d", $p_id);
        $result = $wpdb->get_row($query, ARRAY_A);
        if ($result) {
            return $result['tot'];
        } else {
            return 0;
        }
    }

    public function get_ques_from_p_id($p_id = '') {
        if ($p_id == '') {
            return;
        }
        global $wpdb;
        $pc = new Poll_Class;
        $query = $wpdb->prepare("SELECT p_ques FROM " . $wpdb->prefix . $pc->table . " where p_id = %d", $p_id);
        $result = $wpdb->get_row($query, ARRAY_A);
        if ($result) {
            return sanitize_text_field(stripslashes($result['p_ques']));
        } else {
            return;
        }
    }

    public function answer_data_for_csv($p_id = '', $a_id = '') {
        if ($p_id == '' or $a_id == '') {
            return;
        }
        $total_votes = $this->get_total_votes($p_id);
        $total_votes_by_ans = $this->get_total_votes_by_a_id($a_id);

        if ($total_votes) {
            $ans_per = ((100 / $total_votes) * $total_votes_by_ans);
            $ans_per = number_format($ans_per, 2);
        } else {
            $ans_per = 0;
        }

        $bar = ' (' . $total_votes_by_ans . '/' . $total_votes . ') ';
        return $bar;
    }

    public function get_ans_from_a_id($a_id = '') {
        if ($a_id == '') {
            return;
        }
        global $wpdb;
        $pc = new Poll_Class;
        $query = $wpdb->prepare("SELECT a_ans FROM " . $wpdb->prefix . $pc->table2 . " where a_id = %d", $a_id);
        $result = $wpdb->get_row($query, ARRAY_A);
        if ($result) {
            return sanitize_text_field(stripslashes($result['a_ans']));
        } else {
            return;
        }
    }

}