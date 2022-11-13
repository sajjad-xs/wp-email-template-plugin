<?php

/**
 * @package EmailTemplatePlugin
 */
class CustomMetabox
{

    public $template_types = array();


    public function __construct()
    {
        $this->template_types = array(
            "wc_admin_new_order" => "Woocommerce New Order",
            "wc_cancelled_order" => "Woocommerce Completed Order",
            "wc_failed_order" => "Woocommerce Failed Order",
            "wc_order_on_hold" => "Woocommerce Order On Hold",
            "wc_processing_order" => "Woocommerce Processing Order",
            "wc_completed_order" => "Woocommerce Completed Order",
            "wc_refunded_order" => "Woocommerce Refunded Order",
            "wc_customer_invoice_or_order_details" => "Woocommerce Customer Invoice or Order Details",
            "wc_customer_note" => "Woocommerce Customer Note",
            "wc_reset_password" => "Woocommerce Reset Password",
            "wc_new_account" => "Woocommerce New Account",
            "password_change" => "Password Change",
            "new_user_register" => "New User Register",
            "delete_user" => "Delete User"
        );
        add_action("add_meta_boxes", array($this, 'add_email_template_metabox'));
        add_action('save_post', array($this, 'save_email_template_metabox'));
    }


    /*
        Add a new Metabox - email-template-metabox
        call add_meta_box() function
        add_meta_box( string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, string $context = 'advanced', string $priority = 'default', array $callback_args = null )
    */

    public function add_email_template_metabox()
    {
        add_meta_box("email-template-metabox", "Email Template Meta Box", array($this, 'metabox_fields'), ["email-template"], "advanced", "high", null);
    }


    // Metabox fields
    public function metabox_fields($object)
    {
        wp_nonce_field(basename(__FILE__), "meta-box-nonce");

?>
        <div style="margin-top:20px;">
            <label for="email-template-html" style="font-weight:bold">Email Template HTML</label>
            <br>
            <br>
            <textarea rows="10" cols="50" name="email-template-html" style="width:100% !important;">
            <?= get_post_meta($object->ID, "email_template_html", true) ?>
        </textarea>

            <br>
            <br>
            <label for="email-template-type">Email Template Types</label>
            <br>
            <br>
            <select name="email-template-type" style="width:100% !important;">
                <?php
                foreach ($this->template_types as $key => $template_type) {
                ?>
                    <option value="<?= $key; ?>" <?= $key == get_post_meta($object->ID, "email_template_type", true) ? 'selected' : '' ?>><?php echo $template_type; ?></option>
                <?php
                }
                ?>
            </select>
            <br>
            <br>

            <label for="email-template-status">Status(Active/Inactive): </label>
            <?php
            $status = get_post_meta($object->ID, "email_template_status", true);
            if ($status == 1) {
            ?>
                <input name="email-template-status" type="checkbox" checked>
            <?php
            } else {
            ?>
                <input name="email-template-status" type="checkbox">
            <?php
            }
            ?>

        </div>
<?php
    }

    /*
        metabox fields value is store while the trigger on save draft/publish post
    */
    public function save_email_template_metabox()
    {
        global $post;

        if (isset($post->ID)) {

            //check template html value exists or not
            if (isset($_POST["email-template-html"])) :
                update_post_meta($post->ID, 'email_template_html', $_POST["email-template-html"]);
            endif;

            //check template type value exists or not
            if (isset($_POST["email-template-type"])) :
                update_post_meta($post->ID, 'email_template_type', $_POST["email-template-type"]);
            endif;

            //check template status active or inactive checked or not
            if (isset($_POST["email-template-status"])) {
                update_post_meta($post->ID, 'email_template_status', 1);
            } else {
                update_post_meta($post->ID, 'email_template_status', 0);
            }
        }
    }
}
