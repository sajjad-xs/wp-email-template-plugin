<?php

/**
 * @package EmailTemplatePlugin
 */
/*
Plugin Name: Email Template Plugin
Plugin URI: http://github.com/sajjad385
Description: Customize the default email templates Drag & Drop design/builder for various plugin and text through the WordPress plugin customizer.
Author: Sajjad
Version: 1.0.0
Author URI: http://github.com/sajjad385
License: GPLv2 or later
Text Domain: email-template-plugin
*/


defined('ABSPATH') or die('Something went wrong.');

// $mailer = WC()->mailer(); // get the instance of the WC_Emails class

add_action('phpmailer_init', 'mailtrap');
function mailtrap($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.mailtrap.io';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 2525;
    $phpmailer->Username = '1b0925cee65c26';
    $phpmailer->Password = 'ff926ffbf0ed96';
}


/**
 * Woocommerce template overrride
 */
add_filter('woocommerce_locate_template', 'myplugin_woocommerce_locate_template', 10, 3);

function myplugin_woocommerce_locate_template($template, $template_name, $template_path)
{
    global $woocommerce;
    $_template = $template;
    if (!$template_path) $template_path = $woocommerce->template_url;
    $plugin_path  = myplugin_plugin_path() . '/woocommerce/';
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

function myplugin_plugin_path()
{
    // gets the absolute path to this plugin directory 
    return untrailingslashit(plugin_dir_path(__FILE__));
}



// Register Custom Post Type - Email Template
function email_template()
{
    $labels = array(
        'name'                  => _x('Email Template Post Type', 'Post Type General Name', 'email-template-plugin'),
        'singular_name'         => _x('Email Template Post Type', 'Post Type Singular Name', 'email-template-plugin'),
        'menu_name'             => __('Email Template Post Types', 'email-template-plugin'),
        'name_admin_bar'        => __('Email Template Post Type', 'email-template-plugin'),
        'archives'              => __('Item Archives', 'email-template-plugin'),
        'attributes'            => __('Item Attributes', 'email-template-plugin'),
        'parent_item_colon'     => __('Parent Item:', 'email-template-plugin'),
        'all_items'             => __('All Items', 'email-template-plugin'),
        'add_new_item'          => __('Add New Item', 'email-template-plugin'),
        'add_new'               => __('Add New', 'email-template-plugin'),
        'new_item'              => __('New Item', 'email-template-plugin'),
        'edit_item'             => __('Edit Item', 'email-template-plugin'),
        'update_item'           => __('Update Item', 'email-template-plugin'),
        'view_item'             => __('View Item', 'email-template-plugin'),
        'view_items'            => __('View Items', 'email-template-plugin'),
        'search_items'          => __('Search Item', 'email-template-plugin'),
        'not_found'             => __('Not found', 'email-template-plugin'),
        'not_found_in_trash'    => __('Not found in Trash', 'email-template-plugin'),
        'insert_into_item'      => __('Insert into item', 'email-template-plugin'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'email-template-plugin'),
        'items_list'            => __('Items list', 'email-template-plugin'),
        'items_list_navigation' => __('Items list navigation', 'email-template-plugin'),
        'filter_items_list'     => __('Filter items list', 'email-template-plugin'),
    );
    $args = array(
        'label'                 => __('Email Template Post Type', 'email-template-plugin'),
        'description'           => __('Post Type Description', 'email-template-plugin'),
        'labels'                => $labels,
        'supports'              => array('title'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 10,
        'show_in_admin_bar'      => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type('email-template', $args);
}
add_action('init', 'email_template', 0);

function add_custom_meta_box()
{
    add_meta_box("email-template-metabox", "Email Template Meta Box", "email_template_metabox", ["email-template"], "advanced", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");

function email_template_metabox($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

?>
    <div style="margin-top:20px;">
        <label for="email-template-html" style="font-weight:bold">Email Template HTML</label>
        <br>
        <br>
        <textarea rows="10" cols="50" name="email-template-html" style="width:100% !important;">
            <?= get_post_meta($object->ID, "email_template_html", true); ?>
        </textarea>

        <br>
        <br>
        <label for="email-template-type">Email Template Types</label>
        <br>
        <br>
        <select name="email-template-type" style="width:100% !important;">
            <?php
            $template_types = array(
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

            foreach ($template_types as $key => $template_type) {
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

function cd_save_custom_metabox()
{
    global $post;
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

add_action('save_post', 'cd_save_custom_metabox');
