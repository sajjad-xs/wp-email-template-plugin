<?php

/**
 * @package WPEmailKitPlugin
 */

namespace WPEmailKit;

use WPEmailKit\WPEmailKitCPT;
use WPEmailKit\WPEmailKitMetabox;


class WPEmailKitInit
{

    /**
     * @return array classes array
     */
    public static function get_services()
    {
        return [
            WPEmailDependencyCheck::class,
            WPEmailKitCPT::class,
            WPEmailKitMetabox::class
        ];
    }

    /**
     * Loop through the classes, initialize them,  
     */
    public static function register_services()
    {
        foreach (self::get_services() as $class) {
            self::instantiate($class);
        }
    }
    /**
     * Initialize the class
     * @param class $class      class from the services array
     * @return class instance   new instance of the class
     */
    private static function instantiate($class)
    {
        $service = new  $class();
        return $service;
    }
}
