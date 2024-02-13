<?php
/**
 * WHAT IS THE PURPOSE OF THIS SCRIPT
 */

add_action( 'gform_pre_submission_8', 'gform_pre_submission_8_update_dashboard', 10, 3 );

function gform_pre_submission_8_update_dashboard( $entry, $form ) {

	$user      = get_user_by_email( $entry['102'] );
	$dashboard = DashboardRepository::get_one_for_user( $user->ID );

	BS_Log::info( "DASHBOARD ENTRY: ", $dashboard );

	$dashboard->primaryNameFirst = $entry['101.3'];
	$dashboard->primaryNameLast  = $entry['101.6'];

	$typeOfTicketField = $entry['fields'][6];
	// Accessing the choices within the "Type of Ticket" field
	$choices = $typeOfTicketField->choices;

	// Looping through the choices to find the one with the value '1'
	foreach ( $choices as $choice ) {
		if ( $choice['value'] === '1' ) {
			$dashboard->ticketType = $choice['text'];
			BS_Log::info( "DASHBOARD ENTRY AFTER CHOICE: ", $dashboard );
			DashboardRepository::update( $dashboard );
			break;
		}
	}
}