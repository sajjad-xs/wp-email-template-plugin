<?php

namespace WPEmailKit;

use WPEmailKit\Helpers\Debug;
use WPEmailKit\Helpers\Utils;
use WPEmailKit\Helpers\TemplateTypes;

/**
 * @package WPEmailKitPlugin
 */

class Metabox
{
    public $template_types = array();

    public function __construct()
    {

        $this->template_types = TemplateTypes::list();
        add_action("add_meta_boxes", array($this, 'add'));
        add_action('save_post', array($this, 'save'));
    }

    /**
     * Add a new Metabox - email-template-metabox
     * call add_meta_box() function
     * add_meta_box( string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, string $context = 'advanced', string $priority = 'default', array $callback_args = null )
     */
    public function add()
    {
        add_meta_box("wp-emailkit-metabox", "WP Email Kit Metabox", array($this, 'fields'), ["wp-emailkit"], "advanced", "high", null);
    }

    /**
     * Metabox fields
     */
    public function fields($object)
    {
?>
        <div style="margin-top:20px;">
            <label for="template-html" style="font-weight:bold">Template HTML</label>
            <br>
            <br>
            <textarea id="template-html" rows="10" cols="50" name="wp_emailkit_template_html" style="width:100% !important;"><?php esc_html_e(get_post_meta($object->ID, "wp_emailkit_template_html", true)) ?></textarea>

            <br>
            <br>
            <label for="template-type" style="font-weight:bold">Template Types</label>
            <br>
            <br>
            <select id="template-type" name="wp_emailkit_template_type" style="width:100% !important;">
                <?php
                foreach ($this->template_types as $key => $template_type) {
                ?>
                    <option value="<?php esc_attr_e($key); ?>" <?php echo $key == get_post_meta($object->ID, "wp_emailkit_template_type", true) ? 'selected' : '' ?>>
                        <?php esc_html_e($template_type)  ?>
                    </option>
                <?php
                }
                ?>
            </select>
            <br>
            <br>
            <label for="template-status" style="font-weight:bold">Template Status(Active/Inactive): </label>
            <?php
            $status = esc_html(get_post_meta($object->ID, "wp_emailkit_template_status", true));
            ?>
            <input id="template-status" name="wp_emailkit_template_status" type="checkbox" style="margin-left: 10px; margin-top:4px;" <?php echo $status == true ? 'checked' : '' ?>>

        </div>
<?php
        wp_nonce_field(basename(__FILE__), "meta_box_nonce");
    }

    /*
    *  metabox fields value is store while the trigger on save draft/publish post
    */
    public function save()
    {
        if (!is_user_logged_in() && !current_user_can('administrator')) {
            return;
        }

        if (!$this->checkMetaboxNonce()) {
            return;
        }

        global $post;
        /**
         * check post ID is not null
         */
        if (isset($post->ID)) {
            //check template html value exists or not and update template html
            if (isset($_POST["wp_emailkit_template_html"])) :
                $template_html = Utils::kses($_POST["wp_emailkit_template_html"]);
                update_post_meta($post->ID, 'wp_emailkit_template_html', $template_html);
            endif;

            //check template type value exists or not and update template type
            if (isset($_POST["wp_emailkit_template_type"])) :
                $template_type = sanitize_text_field($_POST["wp_emailkit_template_type"]);
                if (isset($this->template_types[$template_type])) :
                    update_post_meta($post->ID, 'wp_emailkit_template_type', $template_type);
                endif;
            endif;

            //check template status active or inactive checked or not
            if (isset($_POST["wp_emailkit_template_status"])) {
                $type = $_POST["wp_emailkit_template_type"];
                $this->deactivateOthers($type);
                update_post_meta($post->ID, 'wp_emailkit_template_status', 1);
            } else {
                update_post_meta($post->ID, 'wp_emailkit_template_status', 0);
            }
        }
    }

    public function deactivateOthers($type)
    {
        
        $data = array(
            'post_type' => 'wp-emailkit', 
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
                'relation' => 'AND',
                'fields' => 'ids'
            )
        );
       
        $postData = new \WP_Query($data);
        $postIds = wp_list_pluck($postData->posts, 'ID' );
        foreach($postIds as $id) {
            update_post_meta($id, 'wp_emailkit_template_status', 0);
        }
        Debug::log($postIds);
        // update_post_meta($post->ID, 'active_option', true);
        // global $wpdb;
        // $wpdb->query("UPDATE $wpdb->postmeta 
        //                 set `meta_value` = 0 
        //                 where `meta_key` = 'wp_emailkit_template_status'
        //                 and `post_id` 
        //                 in (select wp_postmeta.`post_id` from wp_postmeta 
        //                     where `meta_value` = '$type')");
    }

    /**
     * In actual implementation, you should check whether the $_GET['_wpnonce'] exists. 
     */
    public function checkMetaboxNonce()
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
