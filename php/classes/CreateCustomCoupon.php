<?php

class CreateCustomCoupon {
	public static function create_exhibitor_coupon( $entry, $form, $couponNameFieldValueID, $couponCodeFieldValueID, $totalTickets ) {
		$totalTickets = $totalTickets - 1; //Take one away for the on site contact person that will have already received one
		$coupon       = new GW_Create_Coupon( array(
			'form_id'         => FormIds::FORM_ID_5_BUY_TICKETS,
			'source_field_id' => $couponNameFieldValueID,
			'name_field_id'   => $couponCodeFieldValueID,
			'plugin'          => 'gf',
			'amount'          => 350,
			'type'            => 'flat', // accepts: 'flat', 'percentage'
			'meta'            => array(
				'form_id'          => FormIds::FORM_ID_5_BUY_TICKETS,
				'coupon_limit'     => $totalTickets,
				'coupon_stackable' => false,
			),
		) );
		/**
		 * This function does not use the GW_Create_Coupon class functionality as traditionally intended
		 *  Instead, it creates the object and directly calls the function that creates a Gravity Forms Coupon
		 */
		$coupon->create_coupon_gf( $entry[ $couponNameFieldValueID ], $entry[ $couponCodeFieldValueID ], 100, 'percentage', $entry, $form );

		return $totalTickets;
	}

	public static function create_invited_attendee_coupon( $entry, $form, $couponNameFieldValueID, $couponCodeFieldValueID, $totalTickets ) {
		$couponAmount = $totalTickets * 350;

		$coupon = new GW_Create_Coupon( array(
			'form_id'         => FormIds::FORM_ID_5_BUY_TICKETS,
			'source_field_id' => $couponNameFieldValueID,
			'name_field_id'   => $couponCodeFieldValueID,
			'plugin'          => 'gf',
			'amount'          => $couponAmount,
			'type'            => 'flat', // accepts: 'flat', 'percentage'
			'meta'            => array(
				'form_id'          => FormIds::FORM_ID_5_BUY_TICKETS,
				'coupon_limit'     => 1,
				'coupon_stackable' => false,
			),
		) );
		$coupon->create_coupon_gf( $entry[ $couponNameFieldValueID ], $entry[ $couponCodeFieldValueID ], $couponAmount, 'flat', $entry, $form );

	}

	public static function create_standard_attendee_coupon( $entry, $form, $couponNameFieldValueID, $couponCodeFieldValueID, $totalTickets ) {
		$coupon = new GW_Create_Coupon( array(
			'form_id'         => FormIds::FORM_ID_5_BUY_TICKETS,
			'source_field_id' => $couponNameFieldValueID,
			'name_field_id'   => $couponCodeFieldValueID,
			'plugin'          => 'gf',
			'amount'          => 350,
			'type'            => 'flat', // accepts: 'flat', 'percentage'
			'meta'            => array(
				'form_id'          => FormIds::FORM_ID_5_BUY_TICKETS,
				'coupon_limit'     => $totalTickets,
				'coupon_stackable' => false,
			),
		) );
		$coupon->create_coupon_gf( $entry[ $couponNameFieldValueID ], $entry[ $couponCodeFieldValueID ], 350, 'flat', $entry, $form );
	}

}
