<form name="f" action="" method="post">
<?php $this->process_selector('p_edit');?>
<input type="hidden" name="p_id" id="p_id" value="<?php echo $id;?>" />
<?php wp_nonce_field( 'poll_nonce_action', 'poll_nonce_field' ); ?>
<h1 class="wp-heading-inline"><?php _e('Poll Edit','wp-easy-poll-afo');?></h1>
<table width="100%" border="0" cellspacing="10" class="ap-table">
	<tr>
		<td width="300"><strong><?php _e('Poll Question','wp-easy-poll-afo');?></strong></td>
		<td><input type="text" name="p_ques" value="<?php echo stripslashes($data['p_ques']);?>" class="widefat" placeholder="<?php _e('Enter poll question','wp-easy-poll-afo');?>" required="required" <?php echo $this->is_poll_started($id)==true?'disabled':'';?>/></td>
	</tr>
	<tr>
		<td><strong><?php _e('Author','wp-easy-poll-afo');?></strong></td>
		<td><select name="p_author" <?php echo $this->is_poll_started($id)==true?'disabled':'';?>><?php echo $this->get_poll_author_selected($data['p_author']);?></select></td>
	</tr>
	<tr>
		<td><strong><?php _e('Start','wp-easy-poll-afo');?></strong></td>
		<td><input type="text" name="p_start" id="p_start" value="<?php echo $data['p_start'];?>" placeholder="<?php _e('Poll start time','wp-easy-poll-afo');?>" required="required" <?php echo $this->is_poll_started($id)==true?'disabled':'';?>/><?php $this->dateTimeJsCall('p_start');?></td>
	</tr>
	<tr>
		<td><strong><?php _e('End','wp-easy-poll-afo');?></strong></td>
		<td><input type="text" name="p_end" id="p_end" value="<?php echo $data['p_end'];?>" placeholder="<?php _e('Poll end time','wp-easy-poll-afo');?>" required="required"/><?php $this->dateTimeJsCall('p_end');?><input type="button" name="submit" value="<?php _e('Save','wp-easy-poll-afo');?>" class="button" onclick="updatePollEndDate();" /></td>
	</tr>
	<tr>
		<td><strong><?php _e('Status','wp-easy-poll-afo');?></strong></td>
		<td><select name="p_status" id="p_status"><?php echo $this->get_poll_status_selected($data['p_status']);?></select>
		<input type="button" name="submit" value="<?php _e('Save','wp-easy-poll-afo');?>" class="button" onclick="updatePollStatus();" />
		</td>
	</tr>
	<tr>
		<td><h3><?php _e('Poll Answers','wp-easy-poll-afo');?></h3></td>
		<td><button class="add_more_ans button" <?php echo $this->is_poll_started($id)==true?'disabled':'';?>><?php _e('Add More Answers','wp-easy-poll-afo');?></button></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><div class="ans_fields_wrap">
		<?php 
		if(is_array($data1)){
			foreach($data1 as $key => $value){
				if($this->is_poll_started($id)){
					echo '<div class="p-anss"><input type="text" name="p_anss[]" value="'.stripslashes($value['a_ans']).'" class="widefat with-remove" disabled></div>';
				} else {
					echo '<div class="p-anss"><input type="text" name="p_anss[]" value="'.stripslashes($value['a_ans']).'" class="widefat with-remove"> <a href="#" class="remove_field"><img src="'.plugins_url( WPEPA_DIR_NAME . '/images/delete.png' ).'" alt="X"></a></div>';
				}
				
			}
		}
		?></div>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
		<?php if($this->is_poll_started($id)){
		_e('Poll already started & other than the end date poll cannot be updated now!','wp-easy-poll-afo');
		} else { ?>
		<input type="submit" name="submit" value="<?php _e('Submit','wp-easy-poll-afo');?>" class="button" />
		<?php } ?>
		</td>
	</tr>
	<tr>
		<td><strong>Shortcode</strong></td>
		<td><strong>[easypoll id="<?php echo $id;?>"]</strong></td>
	</tr>
</table>
</form>