<?php

/**
 * @package EmailTemplatePlugin
 */
/*
Plugin Name: Email Template Plugin
Plugin URI: http://github.com/sajjad-xs/wp-email-template-plugin
Description: Customize the default email templates Drag & Drop design/builder for various plugin and text through the WordPress plugin customizer.
Author: Sajjad
Version: 1.0.0
Author URI: http://github.com/sajjad-xs/wp-email-template-plugin
License: GPLv2 or later
Text Domain: email-template-plugin
*/
require_once 'Inc/Init.php';
// If this file is called firectly, abort!!
defined('ABSPATH') or die('You cannot access this resource.');

if ( class_exists(Init::class) ) {
    Init::register_services();
}
