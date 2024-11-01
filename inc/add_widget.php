<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

/**
 * Adds My_Widget widget.
 */
class Chat_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
                'Chat_Widget', // Base ID
                __('Live Group Chat', 'text_domain'));
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {

        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        if (is_user_logged_in()) {
            if (!defined('PLUGIN_PATH_URL')) {
                define('PLUGIN_PATH_URL', plugin_dir_url(__FILE__));
            }
            include_once("chat_widgets.php");
        } else {
            echo "Please login to use live chat";
        }
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'text_domain');
        }
        if (isset($instance['hide_title'])) {
            $hide_title = $instance['hide_title'];
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            <label for="<?php echo $this->get_field_id('hide_title'); ?>"><?php _e('Hide Title:'); ?></label> 
            <input type="checkbox" <?php if ($hide_title == 'yes') { ?> checked="checked" <?php } ?> class="widefat" id="<?php echo $this->get_field_id('hide_title'); ?>" name="<?php echo $this->get_field_name('hide_title'); ?>" value="yes">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['hide_title'] = (!empty($new_instance['hide_title']) ) ? strip_tags($new_instance['hide_title']) : '';
        return $instance;
    }

}
?>