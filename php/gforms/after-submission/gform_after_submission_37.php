<?php
/**
 * sjdfhjk
 */

add_action( 'gform_after_submission_37', 'sb_gform_after_submission_37', 10, 3 );

function sb_gform_after_submission_37( $entry, $form ) {
	// TODO: create entity for this $entry to represent form 37

	BS_Log::info( '/includes/php/gforms/pre-submission/gform_pre_submission_37.php' );
	BS_Log::info( "Function: sb_gform_after_submission_37()" );

	//Submit Onboarding Form
	$dashboard = DashboardRepository::get_one_for_current_user();
	
	$dashboard->entryId37RegisterAttendee = $entry['id'];
	$dashboard->ticketType                = 'Full Access';
	DashboardRepository::update( $dashboard );

}

