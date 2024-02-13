<?php

/**
 * A use case for handling primary attendee registration
 */
class UserRegisteredPrimaryAttendee {

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
	 * Handle primary attendee registration
	 *
	 * @param $user_id
	 * @param  PurchasedTicketEntity  $purchasedTicket
	 *
	 * @return void
	 */
	public function handle( $user_id, PurchasedTicketEntity $purchasedTicket ): void {
		BS_Log::info( 'UserRegisteredPrimaryAttendee', $purchasedTicket );

		$this->createMembership->create_membership_for_attendee_primary( $user_id );

		$dashboard = $this->createDashboard->create_purchased_tickets_dashboard( $user_id, $purchasedTicket );

		$manageGuestsParentEntryID        = ManageGuestsRepository::create( $user_id );
		$dashboard->entryId23ManageGuests = $manageGuestsParentEntryID;

		DashboardRepository::update( $dashboard );
	}
}