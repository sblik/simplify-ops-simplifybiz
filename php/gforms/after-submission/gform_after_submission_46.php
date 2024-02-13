<?php
add_action( 'gform_after_submission_46', 'create_invited_attendee_coupon', 10, 3 );
function create_invited_attendee_coupon( $entry, $form ) {
	BS_Log::info( "after 46 create_invited_attendee_coupon ------------------" );
	BS_Log::info( $entry );

	$totalTickets = $entry['5'];

	CreateCustomCoupon::create_invited_attendee_coupon( $entry, $form, 8, 6, $totalTickets );

	BS_Log::info( "complete ------------------" );
}