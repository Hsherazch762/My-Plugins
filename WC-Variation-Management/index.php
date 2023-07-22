<?php
/*
* Plugin Name: WC Variations Management
* Plugin URI: #
* Author: Sheraz@decklaration.com
* Author URI: #
* Description: This plugin is build for managing product variations.
* Version: 1.0.0
* License: GPL1
* License URI:  #
* Text Domain: WC Variations Management
*/

//If this file is called directly, abort.
if (!defined( 'WPINC' )) {
    die;
}

//Define Constants
if ( !defined('WCVM_PLUGIN_VERSION')) {
    define('WCVM_PLUGIN_VERSION', '1.0.0');
}
if ( !defined('WCVM_PLUGIN_DIR')) {
    define('WCVM_PLUGIN_DIR', plugin_dir_url( __FILE__ ));
}
//Include Scripts & Styles
require plugin_dir_path( __FILE__ ). 'inc/scripts.php';

//Settings Menu & Page
require plugin_dir_path( __FILE__ ). 'inc/settings.php';
?>