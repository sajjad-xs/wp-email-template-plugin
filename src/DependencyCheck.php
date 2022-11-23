<?php
namespace WPEmailKit;

/**
 * @package WPEmailKitPlugin
 */

class DependencyCheck
{
    public $err_message;

    public $wp_version_require = '6.0';

    public $wc_version_require = '6.0';

    public $php_version_require = '8.0';


    public function __construct()
    {
        add_action('init', array($this, 'check'), 0);
    }


    public function check()
    {
        global $wp_version;

        if (version_compare($this->wp_version_require, $wp_version, '>')) {
            $this->err_message = __('Please upgrade WordPress version to', 'wp-emailkit-plugin') . ' ' . $this->wp_version_require;

            return;
        }

        if (version_compare($this->php_version_require, phpversion(), '>')) {
            $this->err_message = __('Please upgrade php version to', 'wp-emailkit-plugin') . ' ' . $this->php_version_require;

            return;
        }

        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            $this->err_message = __('Please install and activate WooCommerce to use', 'wp-emailkit-plugin');
            unset($_GET['activate']);  // phpcs:ignore WordPress.Security.NonceVerification
            deactivate_plugins(plugin_basename(__FILE__));

            return;
        }

        $wc_version = get_option('woocommerce_version');
        if (version_compare($this->wc_version_require, $wc_version, '>')) {
            $this->err_message = __('Please upgrade WooCommerce version to', 'wp-emailkit-plugin') . ' ' . $this->wc_version_require;

            return;
        }
    }
}
