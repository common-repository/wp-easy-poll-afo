<?php

class Poll_Wid extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'poll_wid',
            'Easy Poll Widget',
            array('description' => __('Widget to display selected poll.', 'wp-easy-poll-afo'))
        );
    }

    public function widget($args, $instance) {
        extract($args);
        echo $args['before_widget'];
        $pw = new Poll_Widget_Class;
        $pw->easyPoll($instance);
        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['poll_id'] = sanitize_text_field($new_instance['poll_id']);
        return $instance;
    }

    public function form($instance) {
        $poll_id = @$instance['poll_id'];
        $gc = new General_Poll_Class;
        ?>
		<p><label for="<?php echo $this->get_field_id('poll_id'); ?>"><?php _e('Poll:', 'wp-easy-poll-afo');?> </label>
		<select class="widefat" id="<?php echo $this->get_field_id('poll_id'); ?>" name="<?php echo $this->get_field_name('poll_id'); ?>"><option value="">-</option><?php echo $gc->get_polls_selected($poll_id); ?></select></p>
		<?php
}

}