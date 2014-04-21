<?php 
/* 
Plugin Name: GeoDirectory
Plugin URI: http://wpgeodirectory.com/
Description: GeoDirectory plugin for wordpress.
Version: 1.0.0
Author: GeoDirectory
Author URI: http://wpgeodirectory.com
Requires at least: 3.1
Tested up to: 3.9
*/


define("GEODIRECTORY_VERSION", "1.0.0");

if (!session_id()) session_start();

/**
 * Globals
 **/ 
global $wpdb,$plugin_prefix,$geodir_addon_list;
$plugin_prefix = 'geodir_';


/**
 * Constants
 **/
 if ( !defined('WP_POST_REVISIONS') )
	define( 'WP_POST_REVISIONS', 0);

$geodir_post_custom_fields_cache = array();// This will store the cached post custome fields per package for each page load so not to run for each listing

if (!defined('GEODIRECTORY_TEMPLATE_URL')) define('GEODIRECTORY_TEMPLATE_URL', 'geodirectory/');	

/* ---- Table Names ---- */
if (!defined('GEODIR_COUNTRIES_TABLE')) define('GEODIR_COUNTRIES_TABLE', $plugin_prefix . 'countries' );	
//if (!defined('GEODIR_PRICE_TABLE')) define('GEODIR_PRICE_TABLE', $plugin_prefix . 'price' );	
//if (!defined('GEODIR_INVOICE_TABLE')) define('GEODIR_INVOICE_TABLE', $plugin_prefix . 'invoice' );	
if (!defined('GEODIR_CUSTOM_FIELDS_TABLE')) define('GEODIR_CUSTOM_FIELDS_TABLE', $plugin_prefix . 'custom_fields' );	
if (!defined('GEODIR_ICON_TABLE')) define('GEODIR_ICON_TABLE', $plugin_prefix . 'post_icon' );	
if (!defined('GEODIR_ATTACHMENT_TABLE')) define('GEODIR_ATTACHMENT_TABLE', $plugin_prefix . 'attachments' );	
if (!defined('GEODIR_REVIEW_TABLE')) define('GEODIR_REVIEW_TABLE', $plugin_prefix . 'post_review' );	
if (!defined('GEODIR_CUSTOM_SORT_FIELDS_TABLE')) define('GEODIR_CUSTOM_SORT_FIELDS_TABLE', $plugin_prefix . 'custom_sort_fields' );	

/**
 * Localisation
 **/
if (!defined('GEODIRECTORY_TEXTDOMAIN')) define('GEODIRECTORY_TEXTDOMAIN', 'geodirectory');	
require_once( 'language.php' ); // Define language constants

load_plugin_textdomain('geodirectory', false, dirname( plugin_basename( __FILE__ ) ).'/geodirectory-languages');


/**
 * Include core files
 **/
include_once( 'geodirectory_functions.php' ); 
include_once( 'geodirectory_hooks_actions.php' ); 
include_once( 'geodirectory_widgets.php' ); 
include_once( 'geodirectory_template_tags.php' );

/**
 * Admin init + activation hooks
 **/
if ( is_admin() ) :
	
	require_once( 'geodirectory-admin/admin_functions.php' );
	require_once( 'geodirectory-admin/admin_hooks_actions.php' );
	require_once( 'geodirectory-admin/admin_template_tags.php' );
	if (get_option('geodir_installed') != 1)
	{ 
		require_once( 'geodirectory-admin/admin_install.php' );
		register_activation_hook( __FILE__, 'geodir_activation' );
	} 
	register_deactivation_hook( __FILE__, 'geodir_deactivation' );
	register_uninstall_hook(__FILE__,'geodir_uninstall');
	
endif;