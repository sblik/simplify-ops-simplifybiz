<?php

/**
 * This script generates a name and a code for a coupon which
 *  is saved in the form entry
 *
 *  This is required as to create a coupon using the class GW_Create_Coupon it requires form field IDs and retrieves
 *    the data used for creating a coupon and assigning it a name and code from the entry data.
 */
add_action( 'gform_pre_submission_5', 'sb_gform_pre_submission', 10, 3 );
function sb_gform_pre_submission( $form ) {
	BS_Log::info( "gform_pre_submission_5 ------------------" );

	/**
	 * Check if submission if a Speaker. If it is a speaker,
	 * This input_70 = coupon code
	 * Dashboard fieldId = 14
	 */

	$quantityPurchased = intval( rgpost( 'input_6_3' ) );
	$fName             = rgpost( 'input_26_3' );
	$lName             = rgpost( 'input_26_6' );

	// If no dashboard entry exists for current user OR if the coupon code field in the dashboard entry is empty, AND coupon code is not Speaker code, create a coupon code.
	if ( ( $quantityPurchased > 1 ) ) {
		$_POST['input_85'] = $fName . ' ' . $lName . " Coupon"; //Coupon Name
		$_POST['input_86'] = 'CRYO' . rand( 1, 99999999 ); //Coupon Code
	}

	BS_Log::info( "complete ------------------" );
}