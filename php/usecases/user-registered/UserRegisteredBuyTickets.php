<?php

/**
 * A use case for handling user registration for buy tickets
 */
class UserRegisteredBuyTickets {


	private UserRegisteredAttendeeInvited $userRegisteredAttendeeInvited;
	private UserRegisteredPrimaryAttendee $userRegisteredPrimaryAttendee;
	private UserRegisteredSpeaker $userRegisteredSpeaker;

	public function __construct(
		UserRegisteredAttendeeInvited $userRegisteredAttendeeInvited,
		UserRegisteredPrimaryAttendee $userRegisteredPrimaryAttendee,
		UserRegisteredSpeaker $userRegisteredSpeaker
	) {

		$this->userRegisteredAttendeeInvited = $userRegisteredAttendeeInvited;
		$this->userRegisteredPrimaryAttendee = $userRegisteredPrimaryAttendee;
		$this->userRegisteredSpeaker         = $userRegisteredSpeaker;
	}

	/**
	 * Handle user registration via buy tickets
	 *
	 * @param  PurchasedTicketEntity  $purchasedTicket
	 * @param $userId
	 *
	 * @return void
	 */
	function handle( PurchasedTicketEntity $purchasedTicket, $userId ) {
		$couponCode = $purchasedTicket->couponCodeUsed;
		BS_Log::info( "UserRegisteredBuyTickets coupon code: $couponCode and purchased ticket", $purchasedTicket );

		if ( $purchasedTicket->is_primary() ) {
			$this->userRegisteredPrimaryAttendee->handle( $userId, $purchasedTicket );

			return;
		}

		if ( $purchasedTicket->is_speaker() ) {
			$this->userRegisteredSpeaker->handle( $userId, $purchasedTicket );

			return;
		}

		if ( $purchasedTicket->is_invited() ) {
			$this->userRegisteredAttendeeInvited->handle( $userId, $purchasedTicket );
		}
	}
}