<?php

namespace WPEmailKit\Emails;

use WPEmailKit\Helpers\Debug;
use WPEmailKit\Emails\MailConfig;


defined('ABSPATH') || exit;

/**
 * @package  WPEmailKitPlugin
 */

class NewUserRegister extends MailConfig
{
    public function __construct()
    {
        add_action('init', array($this, 'send'));
    }

    public function send()
    {
        add_filter('wp_new_user_notification_email', array($this, 'newUserMail'), 10, 3);
    }


    public function newUserMail($wp_new_user_notification_email, $user, $blogname)
    {
        Debug::log($wp_new_user_notification_email);
        $data = $this->getPostMeta('new_user_register');
        if ($data) {
            $postMeta = get_post_meta($data->post->ID,  "wp_emailkit_template_html", true);

            $key = get_password_reset_key($user);
            if (is_wp_error($key)) {
                return;
            }
            $loginInfo  = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . "\r\n\r\n";
            $search = ["{{first_name}}", "{{email}}", "{{password}}", "{{login_details}}"];
            $replace   = [
                $user->data->display_name,
                $user->data->user_email,
                $user->data->user_pass,
                $loginInfo

            ];

            $message = str_replace($search, $replace, $postMeta);

            $wp_new_user_notification_email['message'] = $message;
            $wp_new_user_notification_email['headers'] = array(
                "From: XpeedStudio<example@xpeedstudio.com>",
                "Content-Type: text/html; charset=UTF-8"
            );
        }
        return $wp_new_user_notification_email;
    }
}
