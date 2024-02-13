<?php
	
	add_action ( 'gform_pre_submission_43', 'prepare_creation_exhibitor_coupon_entry', 10, 3 );
	function prepare_creation_exhibitor_coupon_entry ( $form ) {
		BS_Log::info ( "pre 43 prepare_creation_exhibitor_coupon_entry ------------------" );
		
		if ( empty( $_POST['input_21'] ) ) {
			$_POST['input_20'] = rgpost ( 'input_6' ) . " Coupon"; //Coupon Name
			$_POST['input_21'] = 'CRYOEX' . rand ( 1, 99999 ); //Coupon Code
		}
		
		BS_Log::info ( "complete ------------------" );
	}