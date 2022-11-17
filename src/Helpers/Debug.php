<?php

namespace WPEmailKit\Helpers;

defined('ABSPATH') || exit;

/**
 * @package  WPEmailKitPlugin
 */

class Debug
{
    public static function log($log)
    {
        echo "<pre>";
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
        echo "</pre>";
    }
}
