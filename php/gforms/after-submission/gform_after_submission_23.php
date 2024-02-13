<?php
/**
 * kjasdsjkahs
 */

add_action( 'gform_after_submission_23', 'sb_gform_after_submission_23', 10, 3 );
function sb_gform_after_submission_23( $entry, $form ) {
	$dashboard = DashboardRepository::get_one_for_current_user();

	$dashboard->entryId5BuyTickets = $entry['id'];
	DashboardRepository::update( $dashboard );
}