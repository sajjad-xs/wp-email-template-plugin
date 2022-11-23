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
            $search = [
                "{{order_id}}", "{{billing_name}}", "{{billing_address}}", "{{billing_email}}",
                "{{shipping_name}}", "{{shipping_address}}", "{{shipping_email}}"
            ];
            $replace = [
                $order_id,
                $order->data['billing']['first_name'] . " " . $order->data['billing']['last_name'],
                $order->data['billing']['address_1'] . " " . $order->data['billing']['address_2'],
                $order->data['billing']['email'],
                $order->data['shipping']['first_name'] . " " . $order->data['shipping']['last_name'],
                $order->data['shipping']['address_1'] . " " . $order->data['shipping']['address_2'],
            ];
            $message = str_replace($search, $replace, $postMeta);

            $tbody =  $this->getTableBody($postMeta);
            $replaceTbody = "";
            foreach ($order->get_items() as $item) {
                $itemSearch = ["{{product_name}}", "{{quantity}}", "{{subtotal}}"];
                $itemReplace = [$item['name'], $item['quantity'], $item['subtotal']];
                $replaceTbody .= str_replace($itemSearch, $itemReplace, $tbody);
            }
            $message = str_replace($tbody, $replaceTbody, $message);

            wp_mail(
                $order->data['billing']['email'],
                "[Email Template Plugin]: New order #$order_id",
                $message,
                array(
                    'From: XpeedStudio<example@xpeedstudio.com>',
                    'Content-Type: text/html; charset=UTF-8'
                )
            );
        }
    }

    public function removePendingToProcessingEmail($email_class)
    {
        // /* remove New order emails */
        remove_action('woocommerce_order_status_pending_to_processing_notification', array($email_class->emails['WC_Email_New_Order'], 'trigger'));
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
