<?php

function process_poll_data(){
		
	if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'delete_p'){
		start_session_if_not_started();
		if ( ! isset( $_REQUEST['poll_nonce'] ) || ! wp_verify_nonce( $_REQUEST['poll_nonce'], 'poll_nonce_action'.sanitize_text_field($_REQUEST['id']) ) ) {
		   wp_die( 'Sorry, your nonce did not verify.');
		} 
		global $wpdb;
		$pc = new Poll_Class;
		$update =  array('p_status' => 'Deleted');
		$data_format = array( '%s' );
		$where = array('p_id' => sanitize_text_field($_REQUEST['id']));
		$data_format1 = array( '%d' );
		$wpdb->update( $wpdb->prefix.$pc->table, $update, $where, $data_format, $data_format1 );
		$pc->add_message('Poll deleted successfully.', 'success');
		wp_redirect($pc->plugin_page);
		exit;
	}
	
	if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'updatePollStatus'){
		start_session_if_not_started();
		if ( ! isset( $_REQUEST['poll_nonce_field'] ) || ! wp_verify_nonce( $_REQUEST['poll_nonce_field'], 'poll_nonce_action') ) {
		echo 'Sorry, your nonce did not verify.';
		exit;
		} 
		
		global $wpdb;
		$pc = new Poll_Class;
		$update =  array('p_status' => sanitize_text_field($_REQUEST['p_status']));
		$data_format = array( '%s' );
		$where = array('p_id' => sanitize_text_field($_REQUEST['p_id']));
		$data_format1 = array( '%d' );
		$wpdb->update( $wpdb->prefix.$pc->table, $update, $where, $data_format, $data_format1 );
		echo 'Poll status updated successfully.';
		exit;
	}
	
	if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'updatePollEndDate'){
		start_session_if_not_started();
		if ( ! isset( $_REQUEST['poll_nonce_field'] ) || ! wp_verify_nonce( $_REQUEST['poll_nonce_field'], 'poll_nonce_action') ) {
		echo 'Sorry, your nonce did not verify.';
		exit;
		} 
		
		global $wpdb;
		$pc = new Poll_Class;
		$update =  array('p_end' => sanitize_text_field($_REQUEST['p_end']));
		$data_format = array( '%s' );
		$where = array('p_id' => sanitize_text_field($_REQUEST['p_id']));
		$data_format1 = array( '%d' );
		$wpdb->update( $wpdb->prefix.$pc->table, $update, $where, $data_format, $data_format1 );
		echo 'Poll end date updated successfully.';
		exit;
	}
	
	if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'p_edit'){
		start_session_if_not_started();
		if ( ! isset( $_REQUEST['poll_nonce_field'] ) || ! wp_verify_nonce( $_REQUEST['poll_nonce_field'], 'poll_nonce_action') ) {
		wp_die( 'Sorry, your nonce did not verify.');
		exit;
		} 
		global $wpdb;
		$pc = new Poll_Class;
		$gc = new General_Poll_Class;
		
		if($gc->is_poll_started(sanitize_text_field($_REQUEST['p_id']))){
			$pc->add_message(__('Poll is started & cannot be updated now!','wp-easy-poll-afo'), 'error');
			wp_redirect($pc->plugin_page."&action=edit&id=".$_REQUEST['p_id']);
			exit;
		}
		
		$update =  array(
		'p_ques' => sanitize_text_field($_REQUEST['p_ques']), 
		'p_author' => sanitize_text_field($_REQUEST['p_author']), 
		'p_start' => sanitize_text_field($_REQUEST['p_start']), 
		'p_end' => sanitize_text_field($_REQUEST['p_end']), 
		'p_status' => sanitize_text_field($_REQUEST['p_status'])
		);
		$data_format = array( 
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		);
		$where = array('p_id' => sanitize_text_field($_REQUEST['p_id']));
		$data_format1 = array( 
		'%d',
		);
		$wpdb->update( $wpdb->prefix.$pc->table, $update, $where, $data_format, $data_format1 );
		
		// remove old answers and add new ones //
		$wpdb->delete( $wpdb->prefix.$pc->table2, $where, $data_format1 );
		$p_anss = $_REQUEST['p_anss'];
		if(is_array($p_anss) and sanitize_text_field($_REQUEST['p_id'])){
			foreach($p_anss as $key => $value){
				if($value != ''){
					$insert1 = array(
					'p_id' => sanitize_text_field($_REQUEST['p_id']), 
					'a_ans' => sanitize_text_field($value), 
					'a_order' => $key+1
					);
					$data_format = array( 
					'%d',
					'%s',
					'%d',
					);
					$wpdb->insert( $wpdb->prefix.$pc->table2, $insert1, $data_format );
				}
			}
		}
		// remove old answers and add new ones //
		
		$pc->add_message(__('Poll updated successfully','wp-easy-poll-afo'), 'success');
		wp_redirect($pc->plugin_page."&action=edit&id=".$_REQUEST['p_id']);
		exit;
	}
	
	if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'p_add'){
		start_session_if_not_started();
		if ( ! isset( $_REQUEST['poll_nonce_field'] ) || ! wp_verify_nonce( $_REQUEST['poll_nonce_field'], 'poll_nonce_action') ) {
		wp_die( 'Sorry, your nonce did not verify.');
		exit;
		} 
		global $wpdb;
		$pc = new Poll_Class;
		$insert = array(
		'p_ques' => sanitize_text_field($_REQUEST['p_ques']), 
		'p_author' => sanitize_text_field($_REQUEST['p_author']), 
		'p_start' => sanitize_text_field($_REQUEST['p_start']), 
		'p_end' => sanitize_text_field($_REQUEST['p_end']), 
		'p_added' => current_time( 'mysql' ), 
		'p_status' => sanitize_text_field($_REQUEST['p_status'])
		);
		$data_format = array( 
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		);
		
		$wpdb->insert( $wpdb->prefix.$pc->table, $insert, $data_format );
		$new_poll_id = $wpdb->insert_id;
		
		$p_anss = $_REQUEST['p_anss'];
		if(is_array($p_anss) and $new_poll_id){
			foreach($p_anss as $key => $value){
				if($value != ''){
					$insert1 = array(
					'p_id' => $new_poll_id, 
					'a_ans' => sanitize_text_field($value), 
					'a_order' => $key+1
					);
					$data_format = array( 
					'%d',
					'%s',
					'%d',
					);
					$wpdb->insert( $wpdb->prefix.$pc->table2, $insert1, $data_format );
				}
			}
		}
		
		$pc->add_message(__('New Poll data added successfully','wp-easy-poll-afo'), 'success');
		wp_redirect($pc->plugin_page."&action=edit&id=".$new_poll_id);
		exit;
	}
	
}

function poll_validate(){
	
	if( isset($_POST['action']) and $_POST['action'] == "submit_poll"){
		start_session_if_not_started();
		global $wpdb;
		$gc = new General_Poll_Class;
		$pc = new Poll_Class;
		$mc = new Message_Class('poll_msg');
		$a_id = sanitize_text_field($_REQUEST['poll_ans']);
		$p_id = $gc->get_p_id_from_a_id($a_id);
		$curr_page_url = sanitize_text_field($_REQUEST['curr_page_url']);
		if($p_id){
			$poll_data = array(
			'p_id' => $p_id, 
			'a_id' => $a_id, 
			'user_id' => get_current_user_id(), 
			'user_ip' => $_SERVER['REMOTE_ADDR'], 
			'v_added' => current_time( 'mysql' ) 
			);
			$data_format = array(
			'%d',
			'%d',
			'%d',
			'%s',
			'%s',
			);
			$res = $gc->save_user_vote($poll_data,$data_format);
			$mc->add_message($res['msg'], $res['res'], $p_id);
			wp_redirect($curr_page_url);
		} else {
			$mc->add_message(__('Poll is empty!','wp-easy-poll-afo'), 'error', sanitize_text_field( $_REQUEST['p_id'] ) );
			wp_redirect($curr_page_url);
		}
		exit;
	}

}