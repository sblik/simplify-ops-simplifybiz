<?php

add_action( 'gform_pre_submission_46', 'prepare_creation_invited_attendee_coupon_entry', 10, 3 );
function prepare_creation_invited_attendee_coupon_entry( $form ) {
	BS_Log::info( "pre 46 prepare_creation_invited_attendee_coupon_entry ------------------" );
	$_POST['input_8'] = rgpost( 'input_1_3' ) . ' ' . rgpost( 'input_1_6' ) . " Coupon"; //Coupon Name
	$_POST['input_6'] = strtoupper( rgpost( 'input_1_3' ) . rgpost( 'input_1_6' ) ) . rand( 1, 99999 ); //Coupon Code
	BS_Log::info( "complete ------------------" );
}