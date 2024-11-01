<form name="sub_search" action="" method="get">
<input type="hidden" name="page" value="<?php echo $this->plugin_page_base;?>" />
<input type="hidden" name="search" value="p_search" />
<table width="100%" border="0">
  <tr>
    <td><input type="text" name="p_ques" value="<?php echo sanitize_text_field(@$_REQUEST['p_ques']);?>" placeholder="<?php _e('Poll','wp-easy-poll-afo');?>"/> <input type="submit" name="submit" value="<?php _e('Filter','wp-easy-poll-afo');?>" class="button"/></td>
  </tr>
</table>
</form>