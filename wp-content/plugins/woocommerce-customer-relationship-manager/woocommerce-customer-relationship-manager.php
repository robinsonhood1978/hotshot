<?php
/**
 * Plugin Name: WooCommerce Customer Relationship Manager |  VestaThemes.com
 * Plugin URI: http://codecanyon.net/item/woocommerce-customer-relationship-manager/5712695&ref=actualityextensions
 * Description: Allows for an overview management of customers and their related accounts as well as managing the communication between your store and them.
 * Version: 3.5.18
 * Author: Actuality Extensions
 * Author URI: http://actualityextensions.com/
 * Tested up to: 5.0.2
 *
 * Text Domain: wc_crm
 * Domain Path: /lang/
 
 * Copyright: (c) 2013-2019 Actuality Extensions (info@actualityextensions.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package     WC-Customer-Relationship-Manager
 * @author      Actuality Extensions
 * @category    Plugin
 * @copyright   Copyright (c) 2013-2018, Actuality Extensions
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * 
 * WC requires at least: 3.5
 * WC tested up to: 3.5.3
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (function_exists('is_multisite') && is_multisite()) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) )
        return;
}else{
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
        return; // Check if WooCommerce is active    
}

// Load plugin class files
require_once( 'includes/class-wc-crm.php' );

require 'updater/updater.php';
global $aebaseapi;
$aebaseapi->add_product(__FILE__);

/**
 * Returns the main instance of WC_CRM to prevent the need to use globals.
 *
 * @since    2.7.0
 * @return object WC_CRM
 */
global $wpdb;
$wpdb->wc_crm_customermeta = $wpdb->prefix . "wc_crm_customermeta";

/**
 * @return WC_CRM $instance;
 */
function WC_CRM () {
	$instance = WC_CRM::instance( __FILE__, '3.5.2' );

	/*if ( is_null( $instance->settings ) ) {
		$instance->settings = WC_CRM_Settings::instance( $instance );
	}*/

	return $instance;
}

WC_CRM();