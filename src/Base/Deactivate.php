<?php

/**
 * @package  WPEmailKitPlugin
 */

namespace WPEmailKit\Base;

class Deactivate
{
    /**
     * Plugin deactivate rewrite rules
     */
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}
