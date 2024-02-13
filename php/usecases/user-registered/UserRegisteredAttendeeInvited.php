<?php

/**
 * A use case for handling user invited attendee registration
 */
class UserRegisteredAttendeeInvited {

	private CreateMembership $createMembership;
	private CreateDashboard $createDashboard;

	public function __construct(
		CreateMembership $createMembership,
		CreateDashboard $createDashboard
	) {
		$this->createMembership = $createMembership;
		$this->createDashboard  = $createDashboard;
	}

	/**
	 * Handle invited attendee registration
	 *
	 * @param $user_id
	 * @param  PurchasedTicketEntity  $purchasedTicket
	 *
	 * @return void
	 */
	public function handle( $user_id, PurchasedTicketEntity $purchasedTicket ): void {
		$this->createMembership->create_membership_for_attendee_invited( $user_id );
		$this->createDashboard->create_invited_attendee_dashboard( $purchasedTicket, $user_id );

		updateDashboard::update_dashboard_entry_attendee_primary_with_invited_guest( $purchasedTicket );
	}
}