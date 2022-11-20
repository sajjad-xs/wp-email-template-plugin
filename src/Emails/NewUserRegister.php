<?php

namespace WPEmailKit\Emails;

defined('ABSPATH') || exit;

/**
 * @package  WPEmailKitPlugin
 */

class NewUserRegister
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
        $data = $this->getPostMeta();
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
    /**
     * @return object|null
     */
    public function getPostMeta()
    {
        $query = array(
            'post_type' => 'wp-emailkit',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => 'wp_emailkit_template_type',
                    'value' => 'new_user_register',
                    'compare' => '=',
                ),
                array(
                    'key' => 'wp_emailkit_template_status',
                    'value' => 1,
                    'compare' => 'exp_eq',
                ),
                'relation' => 'AND'
            )
        );
        return new \WP_Query($query);
    }
}
