<?php

class MailConfig
{
    public $mailer;

    public function __construct()
    {
        $this->mailer =  WC()->mailer();
        add_action('phpmailer_init', array($this, 'mailtrap'));
        add_action('init', array($this, 'remove_wc_actions_and_filters'));
        add_filter('wc_get_template', array($this, 'replace_template_path'), 10, 5);
        add_filter('woocommerce_locate_template', array($this, 'emailkit_woocommerce_locate_template'), 10, 3);
    }

    public function mailtrap($phpmailer)
    {
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '1b0925cee65c26';
        $phpmailer->Password = 'ff926ffbf0ed96';
    }

    public function remove_wc_actions_and_filters()
    {
        remove_action('woocommerce_email_header',  [$this->mailer, 'email_header']);
        remove_action('woocommerce_email_footer',  [$this->mailer, 'email_footer']);
        remove_action('woocommerce_email_before_order_table',  [$this->mailer, 'add_tracking_info_to_emails']);
        remove_action('woocommerce_email_after_order_table', array('WC_Subscriptions_Order', 'add_sub_info_email'), 15, 3);
        remove_action('woocommerce_email_order_details',  [$this->mailer, 'order_details'], 10, 4);
        remove_action('woocommerce_email_order_meta',  [$this->mailer, 'order_meta'], 10, 3);
        remove_action('woocommerce_email_customer_details',  [$this->mailer, 'customer_details'], 10, 3);
        remove_action('woocommerce_email_customer_details',  [$this->mailer, 'email_addresses'], 20, 3);
    }

    public function replace_template_path($located, $template_name, $args, $template_path, $default_path)
    {
        if ($template_name == 'templates/emails/customer-completed-order.php') {
            $located = plugin_dir_path(__FILE__) . 'templates/emails/customer-completed-order.php';
        }
        return $located;
    }

    public function myplugin_plugin_path()
    {
        // gets the absolute path to this plugin directory 
        return untrailingslashit(plugin_dir_path(__FILE__));
    }

    public function emailkit_woocommerce_locate_template($template, $template_name, $template_path)
    {
        global $woocommerce;
        $_template = $template;
        if (!$template_path) $template_path = $woocommerce->template_url;
        $plugin_path  = $this->myplugin_plugin_path() . '/woocommerce/';
        // Look within passed path within the theme - this is priority 
        $template = locate_template(
            array(
                $template_path . $template_name, $template_name
            )
        );
        // Modification: Get the template from this plugin, if it exists 
        if (!$template && file_exists($plugin_path . $template_name))
            $template = $plugin_path . $template_name;

        // Use default template 
        if (!$template)
            $template = $_template;

        // Return what we found 
        return $template;
    }
}
