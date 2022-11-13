<?php

/**
 * @package EmailTemplatePlugin
 */
class EmailTemplateCPT
{
    public function __construct()
    {
        //Actions
        add_action('init', array($this, 'cpt_email_template'), 0);
    }

    // custom post type - email-template
    public function cpt_email_template()
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
            'add_new_item'          => __('Add New Email Template', 'email-template-plugin'),
            'add_new'               => __('Add New', 'email-template-plugin'),
            'new_item'              => __('New Item', 'email-template-plugin'),
            'edit_item'             => __('Edit Email Template', 'email-template-plugin'),
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
}
