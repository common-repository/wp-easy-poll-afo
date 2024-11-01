<?php

if(!function_exists( 'start_session_if_not_started' )){
	function start_session_if_not_started(){
		if(!session_id()){
			@session_start();
		}
	}
}

if(!function_exists( 'wp_easy_poll_text_domain' )){
	function wp_easy_poll_text_domain(){
		load_plugin_textdomain('wp-easy-poll-afo', FALSE, basename( dirname( __FILE__ ) ) .'/languages');
	}
}