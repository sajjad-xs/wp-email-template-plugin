<?php

namespace WPEmailKit;

use WPEmailKit\Helpers\WPEmailKitUtils;
use WPEmailKit\Helpers\WPEmailTemplateTypes;

/**
 * @package WPEmailKitPlugin
 */

class WPEmailKitMetabox
{
    public $template_types = array();

    public function __construct()
    {
        $this->template_types = WPEmailTemplateTypes::types();
        add_action("add_meta_boxes", array($this, 'wp_emailkit_add_metabox'));
        add_action('save_post', array($this, 'wp_emailkit_save_metabox'));
    }

    /**
     * Add a new Metabox - email-template-metabox
     * call add_meta_box() function
     * add_meta_box( string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, string $context = 'advanced', string $priority = 'default', array $callback_args = null )
     */

    public function wp_emailkit_add_metabox()
    {
        add_meta_box("wp-emailkit-metabox", "WP Email Kit Metabox", array($this, 'wp_emailkit_metabox_fields'), ["wp-emailkit"], "advanced", "high", null);
    }


    // Metabox fields
    public function wp_emailkit_metabox_fields($object)
    {
        wp_nonce_field(basename(__FILE__), "meta_box_nonce");

?>
        <div style="margin-top:20px;">
            <label for="wp-emailkit-template-html" style="font-weight:bold">Template HTML</label>
            <br>
            <br>
            <textarea rows="10" cols="50" name="wp-emailkit-template-html" style="width:100% !important;">
            <?= get_post_meta($object->ID, "wp_emailkit_template_html", true) ?>
        </textarea>

            <br>
            <br>
            <label for="wp-emailkit-template-type">Template Types</label>
            <br>
            <br>
            <select name="wp-emailkit-template-type" style="width:100% !important;">
                <?php
                foreach ($this->template_types as $key => $template_type) {
                ?>
                    <option value="<?= $key; ?>" <?= $key == get_post_meta($object->ID, "wp_emailkit_template_type", true) ? 'selected' : '' ?>><?php echo $template_type; ?></option>
                <?php
                }
                ?>
            </select>
            <br>
            <br>

            <label for="wp-emailkit-template-status">Template Status(Active/Inactive): </label>
            <?php
            $status = get_post_meta($object->ID, "wp_emailkit_template_status", true);
            if ($status == 1) {
            ?>
                <input name="wp-emailkit-template-status" type="checkbox" checked>
            <?php
            } else {
            ?>
                <input name="wp-emailkit-template-status" type="checkbox">
            <?php
            }
            ?>

        </div>
<?php
    }

    /*
        metabox fields value is store while the trigger on save draft/publish post
    */
    public function wp_emailkit_save_metabox()
    {
        global $post;

        if (!$this->check_metabox_nonce()) {
            return;
        }
        /**
         * check post ID is not null
         */
        if (isset($post->ID)) {
            //check template html value exists or not
            if (isset($_POST["wp-emailkit-template-html"])) :
                $email_template_html = WPEmailKitUtils::kses($_POST["wp-emailkit-template-html"]);
                update_post_meta($post->ID, 'wp_emailkit_template_html', $email_template_html);
            endif;

            //check template type value exists or not
            if (isset($_POST["wp-emailkit-template-type"])) :
                $email_template_type = sanitize_text_field($_POST["wp-emailkit-template-type"]);
                update_post_meta($post->ID, 'wp_emailkit_template_type', $email_template_type);
            endif;

            //check template status active or inactive checked or not
            if (isset($_POST["wp-emailkit-template-status"])) {
                update_post_meta($post->ID, 'wp_emailkit_template_status', 1);
            } else {
                update_post_meta($post->ID, 'wp_emailkit_template_status', 0);
            }
        }
    }

    /**
     * Nonce validation checking
     */
    public function check_metabox_nonce()
    {
        $is_valid_nonce = (isset($_POST['meta_box_nonce'])
            &&
            wp_verify_nonce($_POST['meta_box_nonce'], basename(__FILE__))) ? true : false;

        if (!$is_valid_nonce) {
            return false;
        }
        return true;
    }
}
