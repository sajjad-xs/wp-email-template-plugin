<?php

namespace WPEmailKit\Helpers;

defined('ABSPATH') || exit;

/**
 * @package  WPEmailKitPlugin
 */

class TemplateTypes
{
    public static function list()
    {
        return  array(
            "wc_admin_new_order"                        => "Woocommerce New Order",
            "wc_cancelled_order"                        => "Woocommerce Cancelled Order",
            "wc_failed_order"                           => "Woocommerce Failed Order",
            "wc_order_on_hold"                          => "Woocommerce Order On Hold",
            "wc_processing_order"                       => "Woocommerce Processing Order",
            "wc_completed_order"                        => "Woocommerce Completed Order",
            "wc_refunded_order"                         => "Woocommerce Refunded Order",
            "wc_customer_invoice_or_order_details"      => "Woocommerce Customer Invoice or Order Details",
            "wc_customer_note"                          => "Woocommerce Customer Note",
            "wc_reset_password"                         => "Woocommerce Reset Password",
            "wc_new_account"                            => "Woocommerce New Account",
            "password_change"                           => "Password Change",
            "new_user_register"                         => "New User Register",
            "delete_user"                               => "Delete User"
        );
    }
}
