<?php
add_filter( 'gform_field_value_ticket_amount', 'return_ticket_amount' );
function return_ticket_amount( $value ) {
	$sponsorFormEntry         = SbFormMethods::get_gform_entry( 24, get_current_user_id(), 'created_by' );
	$buyTicketsFormEntries    = SbFormMethods::get_gform_entries( FormIds::FORM_ID_5_BUY_TICKETS, get_current_user_id(), 'created_by' );
	$parentTicketEntries      = SbFormMethods::get_gform_entries( FormIds::FORM_ID_23_MANAGE_GUESTS, get_current_user_id(), 'created_by' );
	$guestDetails             = GuestDetailsRepository::get_all_for_contact_person( get_current_user_id() );
	$initiateExhibitorEntries = SbFormMethods::get_gform_entries( FormIds::FORM_ID_43_INITITATE_EXHIBITOR_REGISTRATION, get_current_user_id(), 'created_by' );

	foreach ( $parentTicketEntries as $entry ) {// Determine how many nested ticket holder details have been submitted with their parent form
		$values                    = $entry['2'];
		$submittedNestedEntryCount += count( explode( ',', $values ) );
	}
	BS_Log::info( "PARENT TICKET ENTRIES: ", $parentTicketEntries );
	BS_Log::info( "SUBmITTED ENTRY COUNT: $submittedNestedEntryCount" );
	/**
	 *  SPONSORS --------------------------------------------------------------------------
	 */
	$premiumTicketAmount  = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_PREMIUM;
	$platinumTicketAmount = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_PLATINUM;
	$diamondTicketAmount  = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_DIAMOND;
	$goldTicketAmount     = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_GOLD;
	$silverTicketAmount   = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_SILVER;

	$premiumQuantityPurchased  = $sponsorFormEntry['16'];
	$platinumQuantityPurchased = $sponsorFormEntry['17'];
	$diamondQuantityPurchased  = $sponsorFormEntry['18'];
	$goldQuantityPurchased     = $sponsorFormEntry['19'];
	$silverQuantityPurchased   = $sponsorFormEntry['20'];

	$totalTickets = intval( $premiumTicketAmount * $premiumQuantityPurchased ) +
	                intval( $platinumTicketAmount * $platinumQuantityPurchased ) +
	                intval( $diamondTicketAmount * $diamondQuantityPurchased ) +
	                intval( $goldTicketAmount * $goldQuantityPurchased ) +
	                intval( $silverTicketAmount * $silverQuantityPurchased );

	if ( ! empty( $initiateExhibitorEntries ) ) {
		$premiumTicketAmount  = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_PREMIUM;
		$platinumTicketAmount = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_PLATINUM;
		$diamondTicketAmount  = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_DIAMOND;
		$goldTicketAmount     = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_GOLD;
		$silverTicketAmount   = ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_SILVER;


		foreach ( $initiateExhibitorEntries as $entry ) {
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
		}
	}
	/** ------------------------------------------------------------------------------- */

	/**
	 *  Regular Tickets
	 */
	if ( ! empty( $buyTicketsFormEntries ) ) {
		foreach ( $buyTicketsFormEntries as $ticketEntries ) {
			$totalTickets += ( intval( $ticketEntries['6.3'] ) + intval( $ticketEntries['61.3'] ) + intval( $ticketEntries['62.3'] ) + intval( $ticketEntries['65.3'] ) );
		}
		BS_Log::info( "TOTAL TICKETS: $totalTickets" );
		//IF more than one set of tickets has been bought and submitted, subtract submitted amount from total tickets available to assign
		if ( count( $buyTicketsFormEntries ) > 1 ) {
			//$totalTickets = $totalTickets - count($guestDetails);
		}
	}
	//IF contact person is to have a ticket
	/*foreach ( $buyTicketsFormEntries as $ticketEntries ) {
		if ( $ticketEntries['67'] == 1 && !empty( $singleTicketHolderEntry ) ) {
			$totalTickets -= 1;
			break;
		}
	}*/
	BS_Log::info( "TOTAL TICKETS: $totalTickets" );

	return $totalTickets;
}
