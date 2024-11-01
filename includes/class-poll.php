<?php
/*
* Version: 2.0
*/

class Poll_Class extends General_Poll_Class {

    public $plugin_page;

    public $plugin_page_base;

    public $table;

    public $table2;

    public $table3;

    public $column_count;

    public function __construct() {
        $this->plugin_page_base = 'easy_polls';
        $this->plugin_page = admin_url('admin.php?page=' . $this->plugin_page_base);
        $this->table = 'easy_poll_q';
        $this->table2 = 'easy_poll_a';
        $this->table3 = 'easy_poll_votes';
    }

    public function get_table_colums() {
        $colums = array(
            'p_id' => __('ID', 'wp-easy-poll-afo'),
            'p_ques' => __('Poll', 'wp-easy-poll-afo'),
            'p_author' => __('Author', 'wp-easy-poll-afo'),
            'p_start' => __('Start', 'wp-easy-poll-afo'),
            'p_end' => __('End', 'wp-easy-poll-afo'),
            'p_status' => __('Status', 'wp-easy-poll-afo'),
            'action' => __('Action', 'wp-easy-poll-afo'),
        );
        $this->column_count = count($colums);
        return $colums;
    }

    public function add_message($msg, $class = 'error') {
        start_session_if_not_started();
        $_SESSION['msg'] = $msg;
    }

    public function show_message() {
        start_session_if_not_started();
        if (isset($_SESSION['msg']) and $_SESSION['msg']) {
            echo '<div class="updated"><p>' . $_SESSION['msg'] . '</p></div>';
            $_SESSION['msg'] = '';
        }
    }

    public function table_start() {
        return '<table class="wp-list-table widefat ap-table">';
    }

    public function table_end() {
        return '</table>';
    }

    public function get_table_header() {
        $ret = '';
        $header = $this->get_table_colums();
        $ret .= '<thead>';
        $ret .= '<tr>';
        foreach ($header as $key => $value) {
            $ret .= '<td>' . $value . '</td>';
        }
        $ret .= '</tr>';
        $ret .= '</thead>';
        return $ret;
    }

    public function get_table_footer() {
        $ret = '';
        $header = $this->get_table_colums();
        $ret .= '<tfoot>';
        $ret .= '<tr>';
        foreach ($header as $key => $value) {
            $ret .= '<td>' . $value . '</td>';
        }
        $ret .= '</tr>';
        $ret .= '</tfoot>';
        return $ret;
    }

    public function table_td_column($value) {

        $ret = '';
        if (is_array($value)) {
            foreach ($value as $vk => $vv) {
                $ret .= $this->row_data($vk, $vv, $value);
            }
        }

        $ret .= $this->row_actions($value['p_id']);
        return $ret;
    }

    public function row_actions($id) {
        return '<td><a href="' . $this->plugin_page . '&action=edit&id=' . $id . '"><img src="' . plugins_url(WPEPA_DIR_NAME . '/images/edit.png') . '" alt="Edit"></a><a onclick="return confirmRemove();" href="' . wp_nonce_url($this->plugin_page . '&action=delete_p&id=' . $id, 'poll_nonce_action' . $id, 'poll_nonce') . '"><img src="' . plugins_url(WPEPA_DIR_NAME . '/images/delete.png') . '" alt="X"></a></td>';
    }

    public function add_link() {
        return '<a href="' . $this->plugin_page . '&action=add" class="add-new-h2">' . __('Add New Poll', 'wp-easy-poll-afo') . '</a>';
    }

    public function poll_report_link($id) {
        return '<a href="' . $this->plugin_page . '&action=poll_report&p_id=' . $id . '">' . __('View Report', 'wp-easy-poll-afo') . '</a>';
    }

    public function row_data($key, $value, $all) {
        $sh = false;
        switch ($key) {
        case 'p_id':
            $v = '#' . $value . '<br>' . $this->poll_report_link($value);
            $sh = true;
            break;
        case 'p_ques':
            $v = stripslashes($value);
            $sh = true;
            break;
        case 'p_author':
            $v = $this->get_user_name_or_email($value);
            $sh = true;
            break;
        case 'p_start':
            $v = $value;
            $sh = true;
            break;
        case 'p_end':
            $v = $value;
            $sh = true;
            break;
        case 'p_status':
            $v = $this->poll_status_admin($all['p_id']) . ' <strong>(' . $value . ')</strong>';
            $sh = true;
            break;
        default:
            //$v = $value; uncomment this line on your own risk
            break;
        }
        if ($sh) {
            return '<td>' . $v . '</td>';
        }
    }

    public function get_table_body($data) {
        $ret = '';
        $cnt = 0;
        if (is_array($data) and count($data)) {
            $ret .= '<tbody id="the-list">';
            foreach ($data as $k => $v) {
                $ret .= '<tr class="' . ($cnt % 2 == 0 ? 'alternate' : '') . '">';
                $ret .= $this->table_td_column($v);
                $ret .= '</tr>';
                $cnt++;
            }
            $ret .= '</tbody>';
        } else {
            $ret .= '<tbody id="the-list">';
            $ret .= '<tr>';
            $ret .= '<td align="center" colspan="' . $this->column_count . '">' . __('No records found', 'wp-easy-poll-afo') . '</td>';
            $ret .= '</tr>';
            $ret .= '</tbody>';
        }
        return $ret;
    }

    public function get_single_row_data($id) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $this->table . " where p_id = %d", $id);
        $result = $wpdb->get_row($query, ARRAY_A);
        return $result;
    }

    public function get_poll_answers_data($id) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $this->table2 . " where p_id = %d order by a_order", $id);
        $result = $wpdb->get_results($query, ARRAY_A);
        return $result;
    }

    public function search_form() {
        include WPEPA_DIR_PATH . '/view/admin/poll-search.php';
    }

    public function process_selector($v = 'data') {
        echo '<input type="hidden" name="action" value="' . $v . '" />';
    }

    public function get_poll_author_selected($sel_id = '') {
        $authors = get_users();
        $ret = '';
        foreach ($authors as $author) {
            if ($sel_id == $author->ID) {
                $ret .= '<option value="' . $author->ID . '" selected="selected">' . esc_html($author->user_email) . '</option>';
            } else {
                $ret .= '<option value="' . $author->ID . '">' . esc_html($author->user_email) . '</option>';
            }
        }
        return $ret;
    }

    public function get_poll_status_selected($sel_id = '') {
        $statuses = array('Active', 'Inactive', 'Deleted');
        $ret = '';
        foreach ($statuses as $status) {
            if ($sel_id == $status) {
                $ret .= '<option value="' . $status . '" selected="selected">' . $status . '</option>';
            } else {
                $ret .= '<option value="' . $status . '">' . $status . '</option>';
            }
        }
        return $ret;
    }

    public function dateTimeJsCall($id = 'datetime') {?>
	 <script type="text/javascript">
		jQuery(function() {
			jQuery( "#<?php echo $id; ?>" ).datetimepicker({
				dateFormat: "yy-mm-dd",
				timeFormat: "HH:mm:ss",
				beforeShow: function (input, inst) {
					setTimeout(function () {
						inst.dpDiv.css({
							top: jQuery("#<?php echo $id; ?>").offset().top + 35,
							left: jQuery("#<?php echo $id; ?>").offset().left
						});
					}, 0);
				}
			});
		});
	</script>
	<?php }

    public function jQueryDynamicAnswersJs() {?>
	 <script type="text/javascript">
	jQuery(document).ready(function() {
		var max_fields      = 10;
		var wrapper         = jQuery(".ans_fields_wrap");
		var add_button      = jQuery(".add_more_ans");

		var x = wrapper.children('div').length;
		jQuery(add_button).click(function(e){
			e.preventDefault();
			if(x < max_fields){
				x++;
				jQuery(wrapper).append('<div class="p-anss"><input type="text" name="p_anss[]" class="widefat with-remove" placeholder="<?php _e('Enter poll answer', 'wp-easy-poll-afo');?> '+x+'"/> <a href="#" class="remove_field"><img src="<?php echo plugins_url(WPEPA_DIR_NAME . '/images/delete.png'); ?>" alt="X"></a></div>');
			} else {
				alert('<?php _e('Upto 10 answers can be added.', 'wp-easy-poll-afo');?>');
			}
		});

		jQuery(wrapper).on("click",".remove_field", function(e){
			e.preventDefault(); jQuery(this).parent('div').remove(); x--;
		})
	});
	</script>
	<?php }

    public function add() {
        include WPEPA_DIR_PATH . '/view/admin/poll-add.php';
        $this->jQueryDynamicAnswersJs();
    }

    public function edit() {
        $id = sanitize_text_field($_REQUEST['id']);
        $data = $this->get_single_row_data($id);
        $data1 = $this->get_poll_answers_data($id);
        include WPEPA_DIR_PATH . '/view/admin/poll-edit.php';
        $this->jQueryDynamicAnswersJs();
    }

    public function lists() {

        global $wpdb;

        if (isset($_REQUEST['search']) and $_REQUEST['search'] == 'p_search') {
            if (isset($_REQUEST['p_ques'])) {
                $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $this->table . " where p_status <> 'Deleted' and p_ques like %s order by p_added desc", '%' . sanitize_text_field($_REQUEST['p_ques']) . '%');
            }
        } else {
            $query = "SELECT * FROM " . $wpdb->prefix . $this->table . " where p_status <> 'Deleted' order by p_added desc";
        }

        $ap = new AP_Paginate(10);
        $data = $ap->initialize($query, sanitize_text_field(@$_REQUEST['paged']));

        echo '<h1 class="wp-heading-inline">' . __('Polls', 'wp-easy-poll-afo') . $this->add_link() . '</h1>';
        echo $this->search_form();
        echo $this->table_start();
        echo $this->get_table_header();
        echo $this->get_table_body($data);
        echo $this->get_table_footer();
        echo $this->table_end();

        echo '<div style="margin-top:10px;">';
        echo $ap->paginate();
        echo '</div>';

    }

    public function poll_report($p_id = '') {
        include WPEPA_DIR_PATH . '/view/admin/poll-report.php';
    }

    public function get_report($p_id = '') {
        if ($p_id == '') {
            return;
        }
        global $wpdb;
        $ret = '';
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $this->table3 . " where p_id = %d order by v_added desc", $p_id);
        $result = $wpdb->get_results($query, ARRAY_A);
        $p_ans_data = $this->get_poll_answers_data($p_id);

        $ret .= '<h3>' . $this->get_ques_from_p_id($p_id) . '</h3>';
        $ret .= '<table width="100%" class="ap-table">';

        if (is_array($p_ans_data) and count($p_ans_data)) {
            foreach ($p_ans_data as $key => $value) {
                $ret .= '<tr>';
                $ret .= '<td><strong>' . stripslashes($value['a_ans']) . $this->answer_data_for_csv($p_id, $value['a_id']) . '</strong></td>';
                $ret .= '</tr>';
            }
        }
        $ret .= '</table>';

        $ret .= '<table width="100%" class="ap-table">';
        $ret .= '<tr bgcolor="#ccc" style="height:30px;">';
        $ret .= '<td width="25%" align="center"><strong>' . __('Answer', 'wp-easy-poll-afo') . '</strong></td>';
        $ret .= '<td width="25%" align="center"><strong>' . __('User', 'wp-easy-poll-afo') . '</strong></td>';
        $ret .= '<td width="25%" align="center"><strong>' . __('IP', 'wp-easy-poll-afo') . '</strong></td>';
        $ret .= '<td width="25%" align="center"><strong>' . __('Date', 'wp-easy-poll-afo') . '</strong></td>';
        $ret .= '</tr>';

        $cnt = 1;
        if (is_array($result) and count($result)) {
            foreach ($result as $key => $value) {
                $ret .= '<tr bgcolor="' . ($cnt % 2 == 0 ? '#f1f1f1' : '#fff') . '">';
                $ret .= '<td width="25%" align="center">' . $this->get_ans_from_a_id($value['a_id']) . '</td>';
                $ret .= '<td width="25%" align="center">' . $this->get_user_name_or_email($value['user_id']) . '</td>';
                $ret .= '<td width="25%" align="center">' . $value['user_ip'] . '</td>';
                $ret .= '<td width="25%" align="center">' . $value['v_added'] . '</td>';
                $ret .= '</tr>';
                $cnt++;
            }
        } else {
            $ret .= '<tr>';
            $ret .= '<td colspan="4" align="center">' . __('No records found', 'wp-easy-poll-afo') . '</td>';
            $ret .= '</tr>';
        }
        $ret .= '</table>';
        return $ret;
    }

    public function start_wrap() {
        echo '<div class="wrap">';
    }

    public function end_wrap() {
        echo '</div>';
    }

    public function display_list() {
        $this->start_wrap();
        $this->show_message();
        Poll_Settings_Class::help_support();

        if (isset($_REQUEST['action']) and $_REQUEST['action'] == 'edit') {
            $this->edit();
        } elseif (isset($_REQUEST['action']) and $_REQUEST['action'] == 'add') {
            $this->add();
        } elseif (isset($_REQUEST['action']) and $_REQUEST['action'] == 'poll_report') {
            $this->poll_report($_REQUEST['p_id']);
        } else {
            $this->lists();
        }
        $this->end_wrap();
    }
}
