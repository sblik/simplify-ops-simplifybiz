<?php

/**
 * A use case for handling when a user is registered via Gravity Forms
 */

class UserRegistered {

	private UserRegisteredBuyTickets $userRegisteredBuyTickets;
	private UserRegisteredExhibitor $userRegisteredExhibitor;
	private UserRegisteredReassignedAttendee $userRegisteredInvitedAttendee;

	public function __construct(
		UserRegisteredBuyTickets $userRegisteredBuyTickets,
		UserRegisteredExhibitor $userRegisteredExhibitor,
		UserRegisteredReassignedAttendee $userRegisteredInvitedAttendee
	) {
		$this->userRegisteredBuyTickets      = $userRegisteredBuyTickets;
		$this->userRegisteredExhibitor       = $userRegisteredExhibitor;
		$this->userRegisteredInvitedAttendee = $userRegisteredInvitedAttendee;
	}

	/**
	 * Entry method for the use case to handle a user registration.
	 *
	 * @param $userId
	 * @param $feed
	 * @param $entry
	 *
	 * @return void
	 */
	public function handle_user_registration( $userId, $feed, $entry ) {
		BS_Log::info( "UserRegistered in gravity forms: ", $entry );
		$formId = $entry['form_id'];

		if ( $formId == FormIds::FORM_ID_5_BUY_TICKETS ) {
			$this->userRegisteredBuyTickets->handle( new PurchasedTicketEntity( $entry ), $userId );
		}
		if ( $formId == FormIds::FORM_ID_43_INITITATE_EXHIBITOR_REGISTRATION ) {
			$this->userRegisteredExhibitor->handle( new ExhibitorInviteEntity( $entry ), $userId );
		}
		if ( $formId == FormIds::FORM_ID_8_GUEST_DETAILS_REPEATER ) {
			$this->userRegisteredInvitedAttendee->handle( new GuestDetailsEntity( $entry ), $userId );
		}
	}
}