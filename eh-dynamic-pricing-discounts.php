<?php
/**
 	Plugin Name: Dynamic&nbsp;Pricing and Discounts for WooCommerce
	Plugin URI: https://www.xadapter.com/product/dynamic-pricing-discounts-woocommerce/
	Description: This plugin helps you to set discounts and pricing dynamically based on minimum quantity,weight,price and allow you to set maximum allowed discounts on every rule.
	Version: 3.4.2
	Author: xadapter
	Author URI: https://www.xadapter.com
	Copyright: 2016-2017 XAdapter.
*/
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('xa_dy_root_path', plugin_dir_path(__FILE__));
if (!defined('WPINC')) {
    die;
}

if (!class_exists('woocommerce')) {

    add_action('admin_init', 'eh_dp_my_plugin_deactivate');
    if(!function_exists('eh_dp_my_plugin_deactivate')){
        function eh_dp_my_plugin_deactivate() {
            if (!class_exists('woocommerce')) {
                deactivate_plugins(plugin_basename(__FILE__));
                wp_safe_redirect(admin_url('plugins.php'));
            }
        }    
    }
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-xa-dynamic-pricing-plugin-activator.php
 */
if (!function_exists('activate_xa_dynamic_pricing_plugin_premium')) {
    function activate_xa_dynamic_pricing_plugin_premium() {
        if (is_plugin_active('dynamic-pricing-and-discounts-for-woocommerce-basic-version/dynamic-pricing-and-discounts-for-woocommerce-basic-version.php')) {
            deactivate_plugins(basename(__FILE__));
            wp_die(__("Oops! You tried installing the premium version without deactivating and deleting the basic version. Kindly deactivate and delete Dynamic Pricing and Discounts for WooCommerce Basic Version and then try again", "eh-dynamic-pricing-discounts"), "", array('back_link' => 1));
        }
        if (!class_exists('woocommerce')) {
            exit('Please Install and Activate Woocommerce Plugin First, Then Try Again!!');
        }

        require_once plugin_dir_path(__FILE__) . 'includes/class-xa-dynamic-pricing-plugin-activator.php';
        xa_dynamic_pricing_plugin_Activator::activate();
    }

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-xa-dynamic-pricing-plugin-deactivator.php
 */
if (!function_exists('deactivate_xa_dynamic_pricing_plugin_premium')) {

    function deactivate_xa_dynamic_pricing_plugin_premium() {
        if (!class_exists('woocommerce')) {
            new WP_Error('1', 'Dynamic Pricing And Discounts Plugin could not start because WooCommerce Plugin is Deactivated!!');
        }

        require_once plugin_dir_path(__FILE__) . 'includes/class-xa-dynamic-pricing-plugin-deactivator.php';
        xa_dynamic_pricing_plugin_Deactivator::deactivate();
    }

}


register_activation_hook(__FILE__, 'activate_xa_dynamic_pricing_plugin_premium');
register_deactivation_hook(__FILE__, 'deactivate_xa_dynamic_pricing_plugin_premium');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-xa-dynamic-pricing-plugin.php';

add_action('init', 'eh_dy_load_plugin_textdomain');
add_action('init', 'eh_dp_init');

if (!function_exists('eh_dy_load_plugin_textdomain')) {

    function eh_dy_load_plugin_textdomain() {
        load_plugin_textdomain('eh-dynamic-pricing-discounts', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

}
if (!function_exists('eh_dp_init')) {

    function eh_dp_init() {
        if (is_admin()) {
            include("admin/xa_ajax_function.php");
            include("admin/eha_exporter.php");
            include("admin/eha_importer.php");
            include_once ( 'includes/wf_api_manager/wf-api-manager-config.php' );
        }
    }

}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if (!function_exists('run_xa_dynamic_pricing_plugin')) {

    function run_xa_dynamic_pricing_plugin() {
        $plugin = new xa_dynamic_pricing_plugin();
        $plugin->run();
    }

}
global $offers;
$offers = array();
if (!function_exists('eh_dp_plugin_settings_link')) {

    function eh_dp_plugin_settings_link($links) {
        $settings_link = '<a href="admin.php?page=dynamic-pricing-main-page&tab=product_rules">Settings</a>';
        $doc_link = '<a href="https://www.xadapter.com/category/documentation/dynamic-pricing-and-discounts-for-woocommerce/" target="_blank">' . __('Documentation', 'eha_multi_carrier_shipping') . '</a>';
        $support_link = '<a href="https://www.xadapter.com/support/forum/dynamic-pricing-discounts-woocommerce/" target="_blank">' . __('Support', 'eha_multi_carrier_shipping') . '</a>';

        array_unshift($links, $support_link);
        array_unshift($links, $doc_link);
        array_unshift($links, $settings_link);
        return $links;
    }

}


$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'eh_dp_plugin_settings_link');

try {
    run_xa_dynamic_pricing_plugin();
} catch (Exception $e) {

}
