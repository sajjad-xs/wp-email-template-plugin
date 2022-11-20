<?php

namespace WPEmailKit\Emails;

defined('ABSPATH') || exit;


/**
 * @package WPEmailKitPlugin
 */
class WCNewOrder
{
    public function __construct()
    {
        add_action('woocommerce_email', array($this, 'removePendingToProcessingEmail'));
        add_action('init', array($this, 'wcNewOrderMailForAdmin'));
    }

    public function wcNewOrderMailForAdmin()
    {
        add_filter('woocommerce_order_status_pending_to_processing_notification', array($this, 'woocommerceNewOrder'), 10, 2);
    }


    public function woocommerceNewOrder($order_id, $order = false)
    {
        // Debug::log($_POST);
        // Debug::log("Request post method End.");
        // Debug::log($order->data['billing']);
        // Debug::log($order->data['shipping']);
        // Debug::log($order->data['line_items']);
        $data = $this->getPostMeta();
        if ($data) {
            $postMeta = get_post_meta($data->post->ID,  "wp_emailkit_template_html", true);
            $search = ["{{order_id}}", "{{order_date}}"];
            $replace   = [$order_id, $order->date];
            $message = str_replace($search, $replace, $postMeta);
            $sent = wp_mail("sajjad@gmail.com", "[Email Template Plugin]: New order #$order_id", $message, array(
                'From: XpeedStudio<example@xpeedstudio.com>',
                'Content-Type: text/html; charset=UTF-8'
            ));
        }
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
                    'value' => 'wc_processing_order',
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

    public function removePendingToProcessingEmail($email_class)
    {
        // /* remove New order emails */
        remove_action('woocommerce_order_status_pending_to_processing_notification', array($email_class->emails['WC_Email_New_Order'], 'trigger'));
    }
}
