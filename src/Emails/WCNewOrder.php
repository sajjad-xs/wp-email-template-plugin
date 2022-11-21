<?php

namespace WPEmailKit\Emails;

use DOMDocument;
use DOMXPath;
use WPEmailKit\Helpers\Debug;
use WPEmailKit\Emails\MailConfig;

defined('ABSPATH') || exit;


/**
 * @package WPEmailKitPlugin
 */
class WCNewOrder extends MailConfig
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
        $data = $this->getPostMeta('wc_processing_order');
        if ($data) {
            $postMeta = get_post_meta($data->post->ID,  "wp_emailkit_template_html", true);
            // $table = ($postMeta)->find('table', 0);
            $tbody =  $this->getTableBody($postMeta);
            // $getHtml = file_get_html($tbody);
            // $getHtml->find('tbody', 0);

            // $tableTr = $this->str_replace_first("<tbody", "", $tbody);

            $replaceTbody = "";


            // $html = str_get_html($tbody);
            // var_dump($html->find("tbody", 0)->attr);
            // Debug::log($html);


            // die();

            foreach ($order->get_items() as $item) {
                Debug::log($item);
                $replacing = [
                    $item['name'],
                    $item['quantity'],
                    $item['price']
                ];
                $searching = [
                    "{{product_name}}",
                    "{{quantity}}",
                    "{{price}}"
                ];
                $replaceTbody .= str_replace($searching, $replacing, $tbody);
            }
            Debug::log($replaceTbody);

            $search = [
                "{{order_id}}",
                "{{billing_name}}",
                "{{billing_address}}",
                "{{billing_email}}",
                "{{shipping_name}}",
                "{{shipping_address}}",
                "{{shipping_email}}",
            ];
            $replace   = [
                $order_id,
                $order->data['billing']['first_name'] . " " . $order->data['billing']['last_name'],
                $order->data['billing']['address_1'] . " " . $order->data['billing']['address_2'],
                $order->data['billing']['email'],
                $order->data['shipping']['first_name'] . " " . $order->data['shipping']['last_name'],
                $order->data['shipping']['address_1'] . " " . $order->data['shipping']['address_2'],
            ];
            $message = str_replace($search, $replace, $postMeta);
            $message = str_replace($tbody, $replaceTbody, $message);
            // $sent = wp_mail("sajjad@gmail.com", "[Email Template Plugin]: New order #$order_id", $message, array(
            //     'From: XpeedStudio<example@xpeedstudio.com>',
            //     'Content-Type: text/html; charset=UTF-8'
            // ));
        }
    }

    public function removePendingToProcessingEmail($email_class)
    {
        /* remove sending emails during store events */
        remove_action('woocommerce_low_stock_notification', array($email_class, 'low_stock'));
        remove_action('woocommerce_no_stock_notification', array($email_class, 'no_stock'));
        remove_action('woocommerce_product_on_backorder_notification', array($email_class, 'backorder'));

        // /* remove New order emails */
        remove_action('woocommerce_order_status_pending_to_processing_notification', array($email_class->emails['WC_Email_New_Order'], 'trigger'));
        remove_action('woocommerce_order_status_pending_to_completed_notification', array($email_class->emails['WC_Email_New_Order'], 'trigger'));
        remove_action('woocommerce_order_status_pending_to_on-hold_notification', array($email_class->emails['WC_Email_New_Order'], 'trigger'));
        remove_action('woocommerce_order_status_failed_to_processing_notification', array($email_class->emails['WC_Email_New_Order'], 'trigger'));
        remove_action('woocommerce_order_status_failed_to_completed_notification', array($email_class->emails['WC_Email_New_Order'], 'trigger'));
        remove_action('woocommerce_order_status_failed_to_on-hold_notification', array($email_class->emails['WC_Email_New_Order'], 'trigger'));

        // /* remove Processing order emails */
        remove_action('woocommerce_order_status_pending_to_processing_notification', array($email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger'));
        remove_action('woocommerce_order_status_pending_to_on-hold_notification', array($email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger'));

        // /* remove Completed order emails */
        remove_action('woocommerce_order_status_completed_notification', array($email_class->emails['WC_Email_Customer_Completed_Order'], 'trigger'));

        // /* remove Note emails */
        remove_action('woocommerce_new_customer_note_notification', array($email_class->emails['WC_Email_Customer_Note'], 'trigger'));
    }


    public function getTableBody($html)
    {
        $beginningPos = strpos($html, "<tbody");
        $tmpstring = substr($html, $beginningPos);
        $endPos = strpos($tmpstring, "</tbody>");
        if ($beginningPos === false || $endPos === false) {
            return $html;
        }
        return substr($html, $beginningPos, ($endPos + strlen("</tbody>")));
    }
}
