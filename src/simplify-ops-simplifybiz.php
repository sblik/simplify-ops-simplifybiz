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

/**
 * Define constants
 */
define( 'BS_NAME_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BS_NAME_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once BS_NAME_PLUGIN_DIR . 'bs_bootstrap.php';

bootstrap_ops_simplify_plugin();

/**
 * Schedule billable hours report cron on plugin activation
 */
register_activation_hook( __FILE__, 'smplfy_activate_plugin' );
function smplfy_activate_plugin() {
    if ( ! wp_next_scheduled( 'smplfy_send_billable_hours_report' ) ) {
        $denver = new DateTimeZone( 'America/Denver' );
        $now = new DateTime( 'now', $denver );
        $scheduled = new DateTime( 'today 6:00pm', $denver );

        if ( $scheduled <= $now ) {
            $scheduled->modify( '+1 day' );
        }

        wp_schedule_event( $scheduled->getTimestamp(), 'daily', 'smplfy_send_billable_hours_report' );
    }
}

/**
 * Unschedule billable hours report cron on plugin deactivation
 */
register_deactivation_hook( __FILE__, 'smplfy_deactivate_plugin' );
function smplfy_deactivate_plugin() {
    wp_clear_scheduled_hook( 'smplfy_send_billable_hours_report' );
}