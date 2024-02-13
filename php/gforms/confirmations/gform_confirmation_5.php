<?php
/**
 * https://docs.gravityforms.com/gform_confirmation/
 *
 * ## This filter can be used to dynamically change the confirmation message or redirect URL for a form.
 * ## If single ticket is purchased, redirect to Page to Register Single Attendee who is purchaser
 * ## If >1 Ticket is purchased, redirect to Page to Assign Tickets purchased
 *
 */
add_filter( 'gform_confirmation_5', 'sb_gform_confirmation_5', 10, 4 );
function sb_gform_confirmation_5( $confirmation, $form, $entry, $ajax ) {
	BS_Log::info( "/includes/php/gforms/confirmations/gform_confirmation_5.php" );

	$dashboard                   = DashboardRepository::get_one_for_current_user();
	$attendeeOnboardingFormEntry = SbFormMethods::get_gform_entry( FormIds::FORM_ID_26_REGISTER_AS_AN_EXHIBITOR, get_current_user_id(), 'created_by' );

	// Store how many tickets  have been purchased to determine where to redirect the user to on form submission
	$fullAccessQuantity = intval( $entry['6.3'] );

	if ( empty( $attendeeOnboardingFormEntry ) ) {
		$confirmation = array( 'redirect' => SITE_URL . '/ticket-holder-information/' );
	} elseif ( $fullAccessQuantity > 1 ) {
		$confirmation = array( 'redirect' => SITE_URL . '/view/dashboard/entry/' . $dashboard->id );
	}

	BS_Log::info( ">>>" );

	return $confirmation;
}