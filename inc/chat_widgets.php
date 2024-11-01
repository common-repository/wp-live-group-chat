<?php
if ( ! defined('ABSPATH')) exit;  // if direct access
global $wpdb;
?>
<div id="content" class="chat-outer">
    <?php print_r($_SESSION); ?>
    <?php
    if (!empty($instance['hide_title']) AND $instance['hide_title'] == 'yes') {
        
    } else {
        echo '<center><h1>Live Group Chat</h1></center>';
    }
    ?>
    <div class="chatWindow">
        <div class="users">
            <?php
            $current_user = wp_get_current_user();
            echo "Welcome " . ucfirst($current_user->display_name);
            ?>
            <h6>Online Memers</h6>
            <?php
            global $wpdb;
            $table = $wpdb->prefix . "online_user";
            $results = $wpdb->get_results($wpdb->prepare('SELECT distinct(uid), id FROM ' . $table), OBJECT);
            foreach ($results as $val) {
                $onlineUser = get_user_by('id', $val->uid);
                echo '<h5>' . ucfirst($onlineUser->display_name) . '</h5>';
            }
            ?>
        </div>
        <div class="chatbox">
            <div class="status">Online</div>
            <div class="chat">
                <div class="msgs" id="msgsBoxWidget">
                </div>
                <form action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="msgForm msgFormWidget" id="msgFormWidget">
                    <textarea class="msgText" maxlength="60" cols="5" rows="3" name="msg" placeholder="Write your message..."></textarea>
                    <input type="hidden" name="post_by" value="<?php echo ucfirst($current_user->display_name); ?>">
                    <input type="submit" name="send" value="SEND">
                </form>
            </div>
        </div>
    </div>
</div>
