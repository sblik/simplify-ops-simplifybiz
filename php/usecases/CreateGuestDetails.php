<?php

class CreateGuestDetails {

	/**
	 * Create guest details for the purchased ticket
	 *
	 * @param  PurchasedTicketEntity  $purchasedTicket
	 * @param $parentForm
	 *
	 * @return void
	 */
	public static function create_guest_details( PurchasedTicketEntity $purchasedTicket, $parentForm ) {
		// TODO: take in an entity here for the parent form
		$guestDetailsEntity = new GuestDetailsEntity();

		$guestDetailsEntity->formId             = FormIds::FORM_ID_8_GUEST_DETAILS_REPEATER;
		$guestDetailsEntity->createdBy          = $parentForm['created_by'];
		$guestDetailsEntity->email              = $purchasedTicket->email;
		$guestDetailsEntity->legalFirstName     = $purchasedTicket->firstName;
		$guestDetailsEntity->legalLastName      = $purchasedTicket->lastName;
		$guestDetailsEntity->phone              = $purchasedTicket->phone;
		$guestDetailsEntity->ticketType         = TicketType::FULL_ACCESS;
		$guestDetailsEntity->parentKey          = $parentForm['id'];
		$guestDetailsEntity->parentFormKey      = FormIds::FORM_ID_23_MANAGE_GUESTS;
		$guestDetailsEntity->nestedFormFieldKey = 2;

		GuestDetailsRepository::add( $guestDetailsEntity );
	}
}