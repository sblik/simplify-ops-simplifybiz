<?php
/**
 * Plugin Name: Bliksem Operations Plugin
 * Version: 1.0.0
 * Plugin URI: https://github.com/simplifysmallbiz/bliksem-operations-plugin
 * Description: Used to create custom functionality to manage business operations on simplifysmallbiz.com
 * Author: Andre Nell
 * Author URI: http://www.andrenell.me/
 *
 *
 * @package Bliksem
 * @author Andre Nell
 * @since 1.0.0
 */

if (!function_exists('get_option')) {
    header('HTTP/1.0 403 Forbidden');
    die; // Silence is golden, direct call is prohibited
}

/* ****************************************************************
 * ERROR LOGGING
 * ****************************************************************  */
function bs($log)
{
    if (true === WP_DEBUG) {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}

// DEFINE CONSTANTS
define('BS_NAME_VERSION', '1.0.1');
define('BS_NAME_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BS_NAME_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BS_NAME_PLUGIN_BASE_NAME', plugin_basename(__FILE__));
define('BS_NAME_PLUGIN_FILE', basename(__FILE__));
define('BS_NAME_PLUGIN_FULL_PATH', __FILE__);

// IMPLEMENTATION
require_once BS_NAME_PLUGIN_DIR . 'includes/add_gravityform_to_admin_dashboard.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/gravityview_display_label.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/enqueue_scripts.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/gperks_list_fields_as_choices.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/add-organization.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/update_hours_purchased.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/gflow_gform50_step52_approved_update_balances.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/gform_138_submission.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/gform_140_submission.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/gflow_50_approved.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/gform_after_submission_50.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/after_user_created.php';

bs('BS_NAME_PLUGIN_DIR: ' . BS_NAME_PLUGIN_DIR);
