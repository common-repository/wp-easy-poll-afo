<?php
if(!class_exists('Message_Class')){
class Message_Class {
	public $msg_var;
	
	public function __construct( $msg_var = 'msg' ){
		$this->msg_var = $msg_var;
	}
	
	public function add_message( $msg = '', $class = 'updated', $poll_id = '' ){
		start_session_if_not_started();
		$_SESSION[$this->msg_var] = $msg;
		$_SESSION['msg_class'] = $class;
		$_SESSION['poll_id'] = $poll_id;
	}
	
	public function show_message( $poll_id = '' ){
		start_session_if_not_started();
		if(isset($_SESSION[$this->msg_var]) and $_SESSION[$this->msg_var]){
			if( $poll_id == $_SESSION['poll_id'] ){
				echo '<div class="'.$_SESSION['msg_class'].'">'.$_SESSION[$this->msg_var].'</div>';
				unset($_SESSION[$this->msg_var]);
				unset($_SESSION['msg_class']);
				unset($_SESSION['poll_id']);
			}
		}
	}
}
}
