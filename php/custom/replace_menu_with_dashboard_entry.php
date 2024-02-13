<?php

/**
 *  This script manipulates the Dashboard menu item to change the URL to go to
 *      the user's (subscribers/clients) specific single entry gravity view for the dashboard
 */

add_filter( 'wp_nav_menu_objects', 'sb_specific_dashboard_entry_in_menu' );
function sb_specific_dashboard_entry_in_menu( $menu_items ) {

	foreach ( $menu_items as $menu_item ) {
		if ( $menu_item->ID == '8074' ) {
			$dashboard      = DashboardRepository::get_one_for_current_user();
			$menu_item->url = str_replace( 'entryid/', $dashboard->id, $menu_item->url );
		}
	}

	return $menu_items;
}
