<?php

/**
 * @package  WPEmailKitPlugin
 */

namespace WPEmailKit\Base;

class Activate
{

    /**
     * Plugin activate rewrite rules
     */
    public static function activate()
    {
        flush_rewrite_rules();
    }
}
