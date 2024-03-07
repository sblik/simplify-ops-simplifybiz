<?php
/**
 * Plugin Name: Simplify ops.simplifybiz.com Plugin
 * Version: 1.0.0
 * Plugin URI: https://github.com/sblik/simplify-ops-simplifybiz
 * Description: Used to create custom functionality to manage business operations on ops.simplifybiz.com
 * Author: Andre Nell
 * Author URI: http://www.andrenell.me/
 *
 *
 * @package Bliksem
 * @author Andre Nell
 * @since 1.0.0
 */

if ( ! function_exists( 'get_option' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die; // Silence is golden, direct call is prohibited
}

/**
 * Define constants
 */
define( 'SITE_URL', get_site_url() );
define( 'BS_NAME_VERSION', '1.0.1' );
define( 'BS_NAME_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BS_NAME_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BS_NAME_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'BS_NAME_PLUGIN_FILE', basename( __FILE__ ) );
define( 'BS_NAME_PLUGIN_FULL_PATH', __FILE__ );

require_once BS_NAME_PLUGIN_DIR . 'php/utilities/bs_require_utilities.php';
require_once BS_NAME_PLUGIN_DIR . 'bs_bootstrap.php';

bootstrap_ops_simplify_plugin();

