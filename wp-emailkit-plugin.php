<?php

/**
 * @package WPEmailKitPlugin
 */

/*
Plugin Name: WP EmailKit Plugin
Plugin URI: http://github.com/sajjad-xs/wp-email-template-plugin
Description: Customize the default email templates Drag & Drop design/builder for various plugin and text through the WordPress plugin customizer.
Author: Sajjad
Version: 1.0.0
Author URI: http://github.com/sajjad-xs/wp-email-template-plugin
License: GPLv2 or later
Text Domain: email-template-plugin
*/

// If this file is called directly, abort!!
defined('ABSPATH') or die('You cannot access this resource.');

/**
 * Require once the Composer Autoload
 */
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once  dirname(__FILE__) . '/vendor/autoload.php';
}

/**
 * Initialize all the core classes of the plugin
 */
if (class_exists('WPEmailKit\\WPEmailKitInit')) {
    WPEmailKit\WPEmailKitInit::register_services();
}


/**
 * The code that runs during plugin activation
 */
function activate_shsorad_plugin()
{
    // Activate::activate();
}
/**
 * The code that runs during plugin deactivation
 */
function deactivate_shsorad_plugin()
{
    // Deactivate::deactivate();
}
