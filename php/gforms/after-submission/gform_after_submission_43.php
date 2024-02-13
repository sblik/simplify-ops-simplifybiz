<?php
add_action( 'gform_after_submission_43', 'create_exhibitor_coupon', 10, 3 );
function create_exhibitor_coupon( $entry, $form ) {
	BS_Log::info( "after 43 create_exhibitor_coupon ------------------" );
	BS_Log::info( $entry );

	$premiumTicketAmount  = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_PREMIUM;
	$platinumTicketAmount = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_PLATINUM;
	$diamondTicketAmount  = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_DIAMOND;
	$goldTicketAmount     = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_GOLD;
	$silverTicketAmount   = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_SILVER;
	$totalTickets         = 0;

	if ( $entry['14'] == 1 ) {
		$totalTickets += intval( $entry['15'] ) * $premiumTicketAmount;
	}
	if ( $entry['14'] == 2 ) {
		$totalTickets += intval( $entry['15'] ) * $platinumTicketAmount;
	}
	if ( $entry['14'] == 3 ) {
		$totalTickets += intval( $entry['15'] ) * $diamondTicketAmount;
	}
	if ( $entry['14'] == 4 ) {
		$totalTickets += intval( $entry['15'] ) * $goldTicketAmount;
	}
	if ( $entry['14'] == 5 ) {
		$totalTickets += intval( $entry['15'] ) * $silverTicketAmount;
	}
	CreateCustomCoupon::create_exhibitor_coupon( $entry, $form, 20, 21, $totalTickets );


	BS_Log::info( "complete ------------------" );
}