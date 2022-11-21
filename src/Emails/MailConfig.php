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


    public function sendMail()
    {
        $templateType = 'new_user_register';
        if ($templateType == 'new_user_register') {
            add_filter('wp_new_user_notification_email', array($this, 'newUserMail'), 10, 3);
        } else if ($templateType == 'wc_processing_order') {
            add_filter('woocommerce_order_status_pending_to_processing_notification', array($this, 'woocommerceNewOrder'), 10, 2);
        }
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


    // public function woocommerceNewOrder($order_id, $order = false)
    // {
    //     Debug::log($_POST);
    //     Debug::log("Request post method End.");
    //     Debug::log($order->data['billing']);
    //     Debug::log($order->data['shipping']);
    //     Debug::log($order->data['line_items']);
    //     $query = array(
    //         'post_type' => 'wp-emailkit',
    //         'posts_per_page' => 1,
    //         'meta_query' => array(
    //             array(
    //                 'key' => 'wp_emailkit_template_type',
    //                 'value' => 'wc_processing_order',
    //                 'compare' => '=',
    //             ),
    //             array(
    //                 'key' => 'wp_emailkit_template_status',
    //                 'value' => 1,
    //                 'compare' => 'exp_eq',
    //             ),
    //             'relation' => 'AND'
    //         )
    //     );
    //     $data = new \WP_Query($query);
    //     if ($data) {
    //         $postMeta = get_post_meta($data->post->ID,  "wp_emailkit_template_html", true);
    //         $search = ["{{order_id}}", "{{order_date}}"];
    //         $replace   = [$order_id, $order->date];
    //         $message = str_replace($search, $replace, $postMeta);
    //         $sent = wp_mail("sajjad@gmail.com", "[Email Template Plugin]: New order #$order_id", $message, array(
    //             'From: XpeedStudio<example@xpeedstudio.com>',
    //             'Content-Type: text/html; charset=UTF-8'
    //         ));
    //     }
    // }

    // public function remove_woocommerce_emails($email_class)
    // {
    //     // /* remove sending emails during store events */
    //     // remove_action( 'woocommerce_low_stock_notification', array( $email_class, 'low_stock' ) );
    //     // remove_action( 'woocommerce_no_stock_notification', array( $email_class, 'no_stock' ) );
    //     // remove_action( 'woocommerce_product_on_backorder_notification', array( $email_class, 'backorder' ) );

    //     // /* remove New order emails */
    //     remove_action('woocommerce_order_status_pending_to_processing_notification', array($email_class->emails['WC_Email_New_Order'], 'trigger'));
    //     // remove_action( 'woocommerce_order_status_pending_to_completed_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
    //     // remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
    //     // remove_action( 'woocommerce_order_status_failed_to_processing_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
    //     // remove_action( 'woocommerce_order_status_failed_to_completed_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
    //     // remove_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );

    //     // /* remove Processing order emails */
    //     // remove_action( 'woocommerce_order_status_pending_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
    //     // remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );

    //     // /* remove Completed order emails */
    //     // remove_action( 'woocommerce_order_status_completed_notification', array( $email_class->emails['WC_Email_Customer_Completed_Order'], 'trigger' ) );

    //     // /* remove Note emails */
    //     // remove_action( 'woocommerce_new_customer_note_notification', array( $email_class->emails['WC_Email_Customer_Note'], 'trigger' ) );
    // }
}
