<?php

/**
 * @package WPEmailKitPlugin
 */

namespace WPEmailKit;

use WPEmailKit\Cpt;
use WPEmailKit\Emails\NewUserRegister;
use WPEmailKit\Emails\WCNewOrder;
use WPEmailKit\Metabox;
use WPEmailKit\Emails\MailConfig;


class Init
{
    /**
     * @return array classes array
     */
    public static function get_services()
    {
        return [
            DependencyCheck::class,
            MailConfig::class,
            Cpt::class,
            Metabox::class,
            WCNewOrder::class,
            NewUserRegister::class
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

    public function isAuthorize()
    {
        //Hide admin bar for all users except administrators
        if (current_user_can('administrator')) {
            show_admin_bar(true);
        } else {
            // All other users can't view admin bar
            show_admin_bar(false);
        }
    }
}
