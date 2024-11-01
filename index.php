<?php

/*
  Plugin Name: Wp live group Chat
  Description: This is a live group chat plugin with good features. only login user can use it and you can see online user status as well.
  Author: Atul Panchal
  Version: 1.0
 */
if (!defined('ABSPATH'))
    exit;  // if direct access

if (!defined('PLUGIN_PATH')) {
    define('PLUGIN_PATH', plugin_dir_url(__FILE__));
}
wp_enqueue_script('custom_plugin_script', PLUGIN_PATH . 'js/custom_plugin_script.js', array('jquery'));
wp_enqueue_style('custom_plugin_style', PLUGIN_PATH . 'css/custom_plugin_style.css');

if (!function_exists('wp_chat_window')) {

    function wp_chat_window() {
        if (is_user_logged_in()) {
           
            include_once("inc/chat_window.php");
        } else {
            echo "Please login to use live chat";
        }
    }

}
if (!function_exists('submitAction')) {

    function submitAction() {
        $current_user = wp_get_current_user();
        parse_str($_POST["content"]);
        global $wpdb;
        if ($msg != '') {
            $table = $wpdb->prefix . "messages";
            $datach = array(
                'name' => $post_by,
                'msg' => $msg
            );
            $format = array(
                '%s',
                '%s'
            );
            $wpdb->insert($table, $datach, $format);
        }
        $result_mag = array();
        $table = $wpdb->prefix . "messages";
        $results = $wpdb->get_results($wpdb->prepare("SELECT DAY(posted) as postedshow  FROM " . $table . " group by postedshow"), OBJECT);
        foreach ($results as $msg_val) {
            $sql = 'SELECT * FROM ' . $table . ' where DAY(posted) ="' . $msg_val->postedshow . '" ORDER BY mid ASC';
            $result_mag[] = $wpdb->get_results($wpdb->prepare($sql), OBJECT);
        }
        $i = 0;
        foreach ($result_mag as $val_msg_show) {
            echo '<h3 class="date-line">' . date('l d F Y', strtotime($val_msg_show[$i]->posted)) . '</h3>';

            foreach ($val_msg_show as $val_get) {
                if($val_get->name == $current_user->display_name)
                {
                    $cls = 'alg-left';
                }
                else{
                    $cls = 'alg-right';
                }
                echo '<p class="msgRow  ' . $cls . '">[' . date('h:m:s A', strtotime($val_get->posted)) . '] ' . ucfirst($val_get->name) . ': ' . $val_get->msg . '</p>';
            }
            $i++;
        }
        die();
    }

}


if (!function_exists('userLoginHistory')) {

    function userLoginHistory($user_login, $user) {

        global $wpdb;


        $table = $wpdb->prefix . "online_user";
        $datach = array(
            'uid' => $user->ID,
        );
        $format = array(
            '%d',
        );
        $wpdb->insert($table, $datach, $format);
    }

}

if (!function_exists('userLoginHistory')) {

    function userLoginHistory() {
        if (is_user_logged_in()) {
            global $wpdb;
            $current_user = wp_get_current_user();
            $table = $wpdb->prefix . "online_user";
            $where = array('uid' => $current_user->ID);
            $where_format = array('%d');
            $wpdb->delete($table, $where, $where_format = null);

            $table_msg = $wpdb->prefix . "messages";
            $wpdb->query($wpdb->prepare(" DELETE FROM " . $table_msg . " WHERE posted < NOW() - INTERVAL 1 WEEK"));
        }
    }

}
if (!function_exists('chatPluginActivate')) {

    function chatPluginActivate() {
        // Activation code here...
        global $wpdb;
        $wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . 'messages' . " (
			`mid` bigint(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL,
			`msg` text NOT NULL,
			`posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`mid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0"));

        $wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . 'online_user' . " (
			`id` bigint(11) NOT NULL AUTO_INCREMENT,
			`uid` int(11) NOT NULL,
			`user_session_id` bigint(20) NOT NULL,
			`status` int(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0"));
    }

}
if (!function_exists('chatPluginDeactivate')) {

    function chatPluginDeactivate() {
        global $wpdb;
        // Deactvation code here...
        $wpdb->query($wpdb->prepare("DROP TABLE  " . $wpdb->prefix . 'messages'));
        $wpdb->query($wpdb->prepare("DROP TABLE  " . $wpdb->prefix . 'online_user'));
    }

}
if (!function_exists('footerPopupWidget')) {

    function footerPopupWidget() {
        $current_user = wp_get_current_user();
        echo    '<div class="popup-chat" id="divfix">
                    <img class="openWindow" src="' . PLUGIN_PATH . '/images/chat-icon.png"><span class="openWindow" >Live Group Chat</span>
                    <div class="chatWindow popupWindow dn">
                        <div class="chatbox">
                            <div class="status">Online</div>
                            <div class="chat">
                                <div class="msgs" id="msgsBoxpopup">
                                </div>
                                <form action="' . admin_url('admin-ajax.php') . '" method="post" class="msgForm msgFormpopup" id="msgFormpopup">
                                    <textarea class="msgText" maxlength="60" cols="5" rows="3" name="msg" placeholder="Write your message..."></textarea>
                                    <input type="hidden" name="post_by" value="' . ucfirst($current_user->display_name) . '">
                                    <input type="submit" name="send" value="SEND">
                                </form>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>';
    }

}

add_action('widgets_init', function() {
    register_widget('Chat_Widget');
});
include_once('inc/add_widget.php');
register_activation_hook(__FILE__, 'chatPluginActivate');
register_deactivation_hook(__FILE__, 'chatPluginDeactivate');
add_action('wp_logout', 'userLogoutRemove');
add_action('wp_login', 'userLoginHistory', 10, 2);
add_action('wp_ajax_submitAction', 'submitAction');
add_action('wp_ajax_nopriv_submitAction', 'submitAction');
add_shortcode('wp_chat_window', 'wp_chat_window'); 
/* use this shortcode to show chat window [wp_chat_window] and in php code do_shortcode( '[wp_chat_window]' ); */
add_action('wp_head', 'footerPopupWidget');
