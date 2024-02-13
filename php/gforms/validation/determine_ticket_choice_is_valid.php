<?php

/**
 *  This script determine what options to display when assigning a ticket in the ticket holder details repeater
 *      and the single ticket holder details form
 */

// TODO: move into use cases and register with Gravity forms adapter
add_filter( 'gform_field_validation_8_125', 'determine_ticket_choice_is_valid', 10, 4 );
add_filter( 'gform_field_validation_37_124', 'determine_ticket_choice_is_valid', 10, 4 );
function determine_ticket_choice_is_valid( $result, $value, $form, $field ) {
	BS_Log::info( "determine_ticket_choice_is_valid TRIGGERED ---------" );
	$userID  = get_current_user_id();
	$isError = false;
	$formId  = $form['fields'][0]['formId'];

	$ticketTypeFieldId   = null;
	$guestDetailsFieldId = null;

	if ( $formId == FormIds::FORM_ID_8_GUEST_DETAILS_REPEATER ) {
		$ticketTypeFieldId   = GuestDetailsEntity::get_form_id( 'ticketType' );
		$guestDetailsFieldId = GuestDetailsEntity::get_form_id( 'contactPersonId' );
	} elseif ( $formId == FormIds::FORM_ID_37_REGISTER_AS_AN_ATTENDEE ) {
		$ticketTypeFieldId   = 124;
		$guestDetailsFieldId = 'created_by';
	}

	//GET quantities of types of tickets purchased
	$buyTicketsEntries = SbFormMethods::get_gform_entries( FormIds::FORM_ID_5_BUY_TICKETS, $userID, 'created_by' );
	foreach ( $buyTicketsEntries as $ticketEntry ) {
		$fullAccessQuantity += intval( $ticketEntry['6.3'] );
		$day1Quantity       += intval( $ticketEntry['61.3'] );
		$day2Quantity       += intval( $ticketEntry['62.3'] );
		$day3Quantity       += intval( $ticketEntry['65.3'] );
	}
	$sponsorEntries = SbFormMethods::get_gform_entries( 24, $userID, 'created_by' );
	if ( ! empty( $sponsorEntries ) ) {
		foreach ( $sponsorEntries as $sponsor_entry ) {
			$premiumTicketAmount  = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_PREMIUM;
			$platinumTicketAmount = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_PLATINUM;
			$diamondTicketAmount  = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_DIAMOND;
			$goldTicketAmount     = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_GOLD;
			$silverTicketAmount   = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_SILVER;

			$premiumQuantityPurchased  = $sponsor_entry['16'];
			$platinumQuantityPurchased = $sponsor_entry['17'];
			$diamondQuantityPurchased  = $sponsor_entry['18'];
			$goldQuantityPurchased     = $sponsor_entry['19'];
			$silverQuantityPurchased   = $sponsor_entry['20'];

			$fullAccessQuantity += intval( $premiumTicketAmount * $premiumQuantityPurchased ) +
			                       intval( $platinumTicketAmount * $platinumQuantityPurchased ) +
			                       intval( $diamondTicketAmount * $diamondQuantityPurchased ) +
			                       intval( $goldTicketAmount * $goldQuantityPurchased ) +
			                       intval( $silverTicketAmount * $silverQuantityPurchased );
		}
	}

//GET all nested ticket holder details entries and single ticket holder form entry (if applicable)
	// TODO: use GuestDetailsRepository here
	$guestDetails                   = GuestDetailsRepository::get_all( $guestDetailsFieldId, $userID );
	$singleTicketHolderDetailsEntry = SbFormMethods::get_gform_entry( FormIds::FORM_ID_37_REGISTER_AS_AN_ATTENDEE, $userID, 'created_by' );

	BS_Log::info( "TICKET HOLDER DETAILS ENTRIES: ", $guestDetails );
	BS_Log::info( "SINGLE TICKET HOLDER DETAILS ENTRY: ", $singleTicketHolderDetailsEntry );
//Determine from choices selected in nested forms what type of tickets can be assigned
	/*$fullAccessCount = 0;
	$day1Count       = 0;
	$day2Count       = 0;
	$day3Count       = 0;*/

	foreach ( $guestDetails as $guestDetail ) {
		if ( isset( $guestDetail[ $ticketTypeFieldId ] ) ) {
			if ( $guestDetail[ $ticketTypeFieldId ] == 1 ) {
				$fullAccessCount += 1;
			}
			/*			if ( $guestDetail[$ticketTypeFieldId] == 2 ) {
							$day1Count += 1;
						}*/
			if ( $guestDetail[ $ticketTypeFieldId ] == 2 ) {
				$day2Count += 1;
			}
			if ( $guestDetail[ $ticketTypeFieldId ] == 3 ) {
				$day3Count += 1;
			}
		}
	}
	BS_Log::info( "DAY 2 COUNT $day2Count" );
	BS_Log::info( "DAY 2 QUANTITY $day2Quantity" );
	if ( ( $value == 1 && $fullAccessCount >= $fullAccessQuantity ) || ( $value == 2 && $day2Count >= $day2Quantity ) || ( $value == 3 && $day3Count >= $day3Quantity ) ) {
		$isError = true;
	}
	if ( ( $value == 1 && $fullAccessQuantity == 0 ) || ( $value == 2 && $day2Quantity == 0 ) || ( $value == 3 && $day3Quantity == 0 ) ) {
		$message = 'You have not bought any tickets of that type.';
	} else {
		$message = 'You have assigned the maximum amount of that ticket type.';
	}

	//Compare with tickets purchased
//If choice cannot be given, return validation error stating why
	if ( $isError ) {
		$result['is_valid'] = false;
		$result['message']  = $message;
	}

//If choice in form is valid, do nothing

	BS_Log::info( "determine_ticket_choice_is_valid COMPLETE ---------" );

	return $result;
}


