<?php
class Poll_Widget_Class {

    public static function curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if (isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    public function pollSocialShare() {

        $poll_share_enable_facebook = get_option('poll_share_enable_facebook');
        $poll_share_enable_twitter = get_option('poll_share_enable_twitter');
        $poll_share_enable_google = get_option('poll_share_enable_google');
        $poll_share_enable_linkedin = get_option('poll_share_enable_linkedin');
        $poll_share_enable_email = get_option('poll_share_enable_email');

        if ($poll_share_enable_facebook == 'Yes' || $poll_share_enable_twitter == 'Yes' || $poll_share_enable_google == 'Yes' || $poll_share_enable_linkedin == 'Yes' || $poll_share_enable_email == 'Yes') {
            echo '<div class="poll_social">';
        }

        if ($poll_share_enable_facebook == 'Yes') {
            echo '<a href="javascript:void(0);" onclick="openPollShare(\'http://www.facebook.com/sharer/sharer.php?u=' . self::curPageURL() . '\');" class="fa fa-facebook"></a>';
        }

        if ($poll_share_enable_twitter == 'Yes') {
            echo '<a href="javascript:void(0);" onclick="openPollShare(\'http://twitter.com/intent/tweet?status=' . self::curPageURL() . '\');" class="fa fa-twitter"></a>';
        }

        if ($poll_share_enable_google == 'Yes') {
            echo '<a href="javascript:void(0);" onclick="openPollShare(\'https://plus.google.com/share?url=' . self::curPageURL() . '\');" class="fa fa-google"></a>';
        }

        if ($poll_share_enable_linkedin == 'Yes') {
            echo '<a href="javascript:void(0);" onclick="openPollShare(\'http://www.linkedin.com/shareArticle?mini=true&url=' . self::curPageURL() . '\');" class="fa fa-linkedin"></a>';
        }

        if ($poll_share_enable_email == 'Yes') {
            echo '<a href="mailto:?subject=' . get_bloginfo('sitename') . '&body=Check out this site I came across ' . self::curPageURL() . '" class="fa fa-envelope"></a>';
        }

        if ($poll_share_enable_facebook == 'Yes' || $poll_share_enable_twitter == 'Yes' || $poll_share_enable_google == 'Yes' || $poll_share_enable_linkedin == 'Yes' || $poll_share_enable_email == 'Yes') {
            echo '</div>';
        }

    }

    public function easyPoll($instance = array()) {
        global $post;
        $pc = new Poll_Class;
        $mc = new Message_Class('poll_msg');
        $gc = new General_Poll_Class;
        $data = $pc->get_single_row_data($instance['poll_id']);
        $data1 = $pc->get_poll_answers_data($instance['poll_id']);
        $poll_status = $gc->poll_status_message($instance['poll_id']);

        echo '<div class="poll_wrap">';
        $mc->show_message($instance['poll_id']);
        if ($data['p_status'] != 'Active') {
            _e('Poll is Inactive', 'wp-easy-poll-afo');
            return;
        }
        ?>
		<h3><?php echo stripslashes($data['p_ques']); ?></h3>
		<div id="poll_<?php echo $instance['poll_id']; ?>">
			<?php if ($poll_status['status_msg'] == 'active') {?>
				<form name="poll" id="poll" method="post" action="">
					<input type="hidden" name="action" value="submit_poll" />
                    <input type="hidden" name="p_id" value="<?php echo $instance['poll_id']; ?>" />
                    <input type="hidden" name="curr_page_url" value="<?php echo $this->curPageURL(); ?>">
					<?php
if (is_array($data1)) {
            echo '<ul class="poll_list">';
            foreach ($data1 as $key => $value) {
                echo '<li>';
                echo '<label>';
                echo '<input type="radio" name="poll_ans" value="' . $value['a_id'] . '" /> ';
                echo stripslashes($value['a_ans']);
                echo '</label>';
                echo '</li>';
            }
            echo '</ul>';
        }
            ?>
					<div class="poll_submit"><input type="submit" name="submit" value="<?php _e('Submit', 'wp-easy-poll-afo');?>" class="poll_button" /> <?php echo $gc->get_vote_result_link($instance['poll_id']); ?></div>
				</form>
			<?php } else if ($poll_status['status_msg'] == 'voting_over') {?>
                <div class="poll_status_msg"><?php echo $poll_status['msg']; ?>
                	<div class="poll_results"><p><?php echo $gc->get_vote_result_link($instance['poll_id']); ?></p></div>
                </div>
			<?php } else {?>
				<div class="poll_status_msg"><?php echo $poll_status['msg']; ?></div>
			<?php }?>
		</div>

        <?php if ($poll_status['status_msg'] == 'active' || $poll_status['status_msg'] == 'voting_over') {?>
            <div id="poll_ans_<?php echo $instance['poll_id']; ?>" class="poll_ans_wrap" style="display:none;">
                <?php echo $gc->voting_result($instance['poll_id']); ?>
                <input type="button" class="poll_button" onclick="LoadPollForm('<?php echo $instance['poll_id']; ?>')" value="<?php _e('Back', 'wp-easy-poll-afo');?>">
            </div>
            <?php $this->pollSocialShare();?>
        <?php }?>

		<?php
echo '</div>';
    }

}
