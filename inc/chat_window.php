<?php
if ( ! defined('ABSPATH')) exit;  // if direct access
global $wpdb;

$result_mag = array();
$table = $wpdb->prefix . "messages";
$results = $wpdb->get_results($wpdb->prepare("SELECT DAY(posted) as postedshow  FROM " . $table . " group by postedshow"), OBJECT);
foreach ($results as $msg_val) {
    $sql = 'SELECT * FROM ' . $table . ' where DAY(posted) ="' . $msg_val->postedshow . '" ORDER BY mid ASC';
    $result_mag[] = $wpdb->get_results($wpdb->prepare($sql), OBJECT);
}
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
                <div class="msgs" id="msgsBox">
                    <?php
                    global $wpdb;
                    $i = 0;
                    foreach ($result_mag as $val_msg_show) {
                        echo '<h3 class="date-line">' . date('l d F Y', strtotime($val_msg_show[$i]->posted)) . '</h3>';
                        foreach ($val_msg_show as $val_get) {
                            echo '<p class="msgRow ' . $cls . '">[' . date('h:m:s A', strtotime($val_get->posted)) . '] ' . ucfirst($val_get->name) . ': ' . $val_get->msg . '</p>';
                        }
                        $i++;
                    }
                    ?>
                </div>
                <form action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="msgForm" id="msgForm">
                    <textarea class="msgText" maxlength="60" cols="5" rows="3" name="msg" placeholder="Write your message..."></textarea>
                    <input type="hidden" name="post_by" value="<?php echo ucfirst($current_user->display_name); ?>">
                    <input type="submit" name="send" value="SEND">
                </form>
            </div>
        </div>
    </div>
</div>
