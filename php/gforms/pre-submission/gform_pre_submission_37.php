<?php
/**
 * https://docs.gravityforms.com/gform_pre_submission/
 * This action hook is executed after form validation, but before any notifications are sent and the entry is stored. This action can be used to modify the posted values prior to creating the entry.
 *
 * Store the text value of the type of ticket selected in the dashboard entry
 * So that when an employee looks at the ticket holders they can see which type of ticket they should have.
 */

add_action( 'gform_pre_submission_37', 'gform_pre_submission_37_update_dashboard', 10, 3 );
function gform_pre_submission_37_update_dashboard( $entry, $form ) {
	BS_Log::info( '/includes/php/gforms/pre-submission/gform_pre_submission_37.php' );

	$dashboard         = DashboardRepository::get_one_for_current_user();
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

	BS_Log::info( '>>>' );

}