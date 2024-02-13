<?php
add_action( 'gform_after_submission_5', 'sb_after_submission_5', 10, 3 );
function sb_after_submission_5( $entry, $form ) {
	BS_Log::info( "after 5 create_standard_attendee_coupon ------------------" );
	BS_Log::info( $entry );

	create_standard_attendee_coupon( $entry, $form );

	BS_Log::info( "complete ------------------" );
}

function create_standard_attendee_coupon( $entry, $form ) {
	$purchasedTicket = new PurchasedTicketEntity( $entry );

	$dashboard = DashboardRepository::get_by_stored_coupon_code( $purchasedTicket->couponCodeUsed );

	$totalTickets = $purchasedTicket->totalTickets;
	if ( ( empty( $dashboard ) || $dashboard->storedCouponCode == '' ) && $purchasedTicket->couponCodeUsed !== strtoupper( AttendeeCodes::SPEAKER_CODE ) ) {
		$totalTickets = $totalTickets - 1;
		if ( $totalTickets >= 1 ) {
			CreateCustomCoupon::create_standard_attendee_coupon( $entry, $form, 85, 86, $totalTickets );
		}
	}
}