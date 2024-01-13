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

if (!function_exists('get_option')) {
    header('HTTP/1.0 403 Forbidden');
    die; // Silence is golden, direct call is prohibited
}

/**
 * Include scripts used throughout plugin
 */
require_once BS_NAME_PLUGIN_DIR . 'includes/constants.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/enqueue_scripts.php';
require_once BS_NAME_PLUGIN_DIR . 'includes/globals.php';

// Include Helpers
require_once BS_NAME_PLUGIN_DIR . 'helpers/gperks_list_fields_as_choices.php';
require_once BS_NAME_PLUGIN_DIR . 'helpers/gform_ids.php';
require_once BS_NAME_PLUGIN_DIR . 'helpers/gravityview_display_label.php';
require_once  BS_NAME_PLUGIN_DIR . 'helpers/gravityflow_inbox_sort_order.php';

/**
 * Include Triggers
 */
require_once BS_NAME_PLUGIN_DIR . 'triggers/gflow_step_complete.php';
//require_once BS_NAME_PLUGIN_DIR . 'triggers/gform_50_submission_ops_work_completed.php';
require_once BS_NAME_PLUGIN_DIR . 'triggers/gform_140_submission_utility_trigger_action.php';
require_once BS_NAME_PLUGIN_DIR . 'triggers/profile_updated.php';
//TODO Track hours

