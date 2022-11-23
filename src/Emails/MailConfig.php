<?php

/**
 * @package WPEmailKitPlugin
 */

namespace WPEmailKit\Emails;

use WPEmailKit\Helpers\Debug;

class MailConfig
{
    public function __construct()
    {
        add_action('phpmailer_init', array($this, 'mailtrap'));
    }

    public function mailtrap($phpmailer)
    {
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '178d2834331ade';
        $phpmailer->Password = '03c85f857a5234';
    }
    /**
     * @return object|null
     */
    public function getPostMeta($type)
    {
        $query = array(
            'post_type' => 'wp-emailkit',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => 'wp_emailkit_template_type',
                    'value' => $type,
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
