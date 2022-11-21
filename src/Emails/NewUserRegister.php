<?php

namespace WPEmailKit\Emails;

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
        $data = $this->getPostMeta('new_user_register');
        if ($data) {
            $postMeta = get_post_meta($data->post->ID,  "wp_emailkit_template_html", true);

            $search = ["{{first_name}}", "{{email}}", "{{password}}"];
            $replace   = [$user->data->display_name, $user->data->user_email, $user->data->user_pass];
            $message = str_replace($search, $replace, $postMeta);

            $wp_new_user_notification_email['message'] = $message;
            $wp_new_user_notification_email['headers'] = array(
                'From: XpeedStudio<example@xpeedstudio.com>',
                'Content-Type: text/html; charset=UTF-8'
            );
        }
        return $wp_new_user_notification_email;
    }
}
