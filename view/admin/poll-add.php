<form name="f" action="" method="post">
<?php $this->process_selector('p_add');?>
<?php wp_nonce_field( 'poll_nonce_action', 'poll_nonce_field' ); ?>
<h1 class="wp-heading-inline"><?php _e('Poll Add','wp-easy-poll-afo');?></h1>
<table width="100%" border="0" cellspacing="10" class="ap-table">
	<tr>
		<td width="300"><strong><?php _e('Poll Question','wp-easy-poll-afo');?></strong></td>
		<td><input type="text" name="p_ques" class="widefat" placeholder="<?php _e('Enter poll question','wp-easy-poll-afo');?>" required="required" /></td>
	</tr>
	<tr>
		<td><strong><?php _e('Author','wp-easy-poll-afo');?></strong></td>
		<td><select name="p_author" class="widefat"><?php echo $this->get_poll_author_selected();?></select></td>
	</tr>
	<tr>
		<td><strong><?php _e('Start','wp-easy-poll-afo');?></strong></td>
		<td><input type="text" name="p_start" id="p_start" placeholder="<?php _e('Poll start time','wp-easy-poll-afo');?>" required="required"/><?php $this->dateTimeJsCall('p_start');?></td>
	</tr>
	<tr>
		<td><strong><?php _e('End','wp-easy-poll-afo');?></strong></td>
		<td><input type="text" name="p_end" id="p_end" placeholder="<?php _e('Poll end time','wp-easy-poll-afo');?>" required="required"/><?php $this->dateTimeJsCall('p_end');?></td>
	</tr>
	<tr>
		<td><strong><?php _e('Status','wp-easy-poll-afo');?></strong></td>
		<td><select name="p_status"><?php echo $this->get_poll_status_selected();?></select></td>
	</tr>
	<tr>
		<td><h3><?php _e('Poll Answers','wp-easy-poll-afo');?></h3></td>
		<td><button class="add_more_ans button"><?php _e('Add More Answers','wp-easy-poll-afo');?></button></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><div class="ans_fields_wrap"><div><p><input type="text" name="p_anss[]" class="widefat with-remove" placeholder="<?php _e('Enter poll answer','wp-easy-poll-afo');?> 1"></p></div></div></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="<?php _e('Submit','wp-easy-poll-afo');?>" class="button" /></td>
	</tr>
</table>
</form>