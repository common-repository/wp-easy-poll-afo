<form name="f" method="post" action="">
<input type="hidden" name="option" value="easy_poll_afo_options_save_settings" />
<?php wp_nonce_field( 'easy_poll_afo_options_save_action', 'easy_poll_afo_options_save_action_field' ); ?>
<table border="0" width="100%" class="ap-table">
  
  <tr>
    <td width="300"><h3><?php _e('Poll Settings','wp-easy-poll-afo');?></h3></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="2">
     <div class="ap-tabs">
        <div class="ap-tab"><?php _e('General','wp-easy-poll-afo');?></div>
        <div class="ap-tab"><?php _e('Social Shares','wp-easy-poll-afo');?></div>
    </div>

     <div class="ap-tabs-content">
        <div class="ap-tab-content">
        <table width="100%">
          <tr>
            <td valign="top" width="300"><strong><?php _e('View Poll Result After Poll Ends','wp-easy-poll-afo');?></strong></td>
            <td><label><input type="checkbox" name="poll_result_after_it_ends" value="Yes" <?php echo $poll_result_after_it_ends == 'Yes'?'checked="checked"':'';?> /> <?php _e('Check this so that users can view the poll results only after the poll ends.','wp-easy-poll-afo');?></label></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="<?php _e('Save','wp-easy-poll-afo');?>" class="button button-primary button-large button-ap-large" /></td>
          </tr>
          </table>
        </div>
        <div class="ap-tab-content">
        <table width="100%">
          <tr>
            <td valign="top" width="300"><strong><?php _e('Facebook','wp-easy-poll-afo');?></strong></td>
            <td><label><input type="checkbox" name="poll_share_enable_facebook" value="Yes" <?php echo $poll_share_enable_facebook == 'Yes'?'checked="checked"':'';?> /><i><?php _e('Enable Facebook Share','wp-easy-poll-afo');?></i></label></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" width="300"><strong><?php _e('Twitter','wp-easy-poll-afo');?></strong></td>
            <td><label><input type="checkbox" name="poll_share_enable_twitter" value="Yes" <?php echo $poll_share_enable_twitter == 'Yes'?'checked="checked"':'';?> /><i><?php _e('Enable Twitter Share','wp-easy-poll-afo');?></i></label></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" width="300"><strong><?php _e('Google','wp-easy-poll-afo');?></strong></td>
            <td><label><input type="checkbox" name="poll_share_enable_google" value="Yes" <?php echo $poll_share_enable_google == 'Yes'?'checked="checked"':'';?> /><i><?php _e('Enable Google Share','wp-easy-poll-afo');?></i></label></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" width="300"><strong><?php _e('Linked In','wp-easy-poll-afo');?></strong></td>
            <td><label><input type="checkbox" name="poll_share_enable_linkedin" value="Yes" <?php echo $poll_share_enable_linkedin == 'Yes'?'checked="checked"':'';?> /><i><?php _e('Enable Linked In Share','wp-easy-poll-afo');?></i></label></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" width="300"><strong><?php _e('Email','wp-easy-poll-afo');?></strong></td>
            <td><label><input type="checkbox" name="poll_share_enable_email" value="Yes" <?php echo $poll_share_enable_email == 'Yes'?'checked="checked"':'';?> /> <i><?php _e('Enable Email In Share','wp-easy-poll-afo');?></i></label></td>
          </tr>
           <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="<?php _e('Save','wp-easy-poll-afo');?>" class="button button-primary button-large button-ap-large" /></td>
          </tr>
          </table>
        </div>
    </div>
  </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>