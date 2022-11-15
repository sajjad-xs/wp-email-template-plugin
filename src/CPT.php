<?php

/**
 * @package WPEmailKitPlugin
 */

namespace WPEmailKit;

class CPT
{
    public function __construct()
    {
        add_action('init', array($this, 'register'), 0);
    }

    /**
     *register custom post type -( wp-emailkit ) 
     */
    public function register()
    {
        $labels = array(
            'name'                  => _x('WP EmailKit', 'Post Type General Name', 'wp-emailkit'),
            'singular_name'         => _x('WP EmailKit', 'Post Type Singular Name', 'wp-emailkit'),
            'menu_name'             => __('WP EmailKit', 'wp-emailkit'),
            'name_admin_bar'        => __('WP EmailKit', 'wp-emailkit'),
            'archives'              => __('Item Archives', 'wp-emailkit'),
            'attributes'            => __('Item Attributes', 'wp-emailkit'),
            'parent_item_colon'     => __('Parent Item:', 'wp-emailkit'),
            'all_items'             => __('All Templates', 'wp-emailkit'),
            'add_new_item'          => __('Add New WP Email Template', 'wp-emailkit'),
            'add_new'               => __('Add New', 'wp-emailkit'),
            'new_item'              => __('New Item', 'wp-emailkit'),
            'edit_item'             => __('Edit WP EmailKit Template', 'wp-emailkit'),
            'update_item'           => __('Update Template', 'wp-emailkit'),
            'view_item'             => __('View Template', 'wp-emailkit'),
            'view_items'            => __('View Templates', 'wp-emailkit'),
            'search_items'          => __('Search Template', 'wp-emailkit'),
            'not_found'             => __('Not found', 'wp-emailkit'),
            'not_found_in_trash'    => __('Not found in Trash', 'wp-emailkit'),
            'insert_into_item'      => __('Insert into item', 'wp-emailkit'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'wp-emailkit'),
            'items_list'            => __('Items list', 'wp-emailkit'),
            'items_list_navigation' => __('Items list navigation', 'wp-emailkit'),
            'filter_items_list'     => __('Filter items list', 'wp-emailkit'),
        );
        $args = array(
            'label'                 => __('WP EmailKit', 'wp-emailkit'),
            'description'           => __('Post Type Description', 'wp-emailkit'),
            'labels'                => $labels,
            'supports'              => array('title'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'           => true,
            'menu_position'         => 10,
            'show_in_admin_bar'      => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page'
        );
        register_post_type('wp-emailkit', $args);
    }
}
