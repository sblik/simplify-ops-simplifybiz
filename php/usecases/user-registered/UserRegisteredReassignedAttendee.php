<?php

/**
 * A use case for handling reassigned attendee registration
 */

class UserRegisteredReassignedAttendee {

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
	 * Handle reassigned attendee registration
	 *
	 * @param  GuestDetailsEntity  $guestDetails
	 * @param $userId
	 *
	 * @return void
	 */
	function handle( GuestDetailsEntity $guestDetails, $userId ): void {
		BS_Log::info( "UserRegisteredReassignedAttendee", $guestDetails );

		$this->createMembership->create_membership_for_attendee_invited( $userId );
		$this->createDashboard->created_reassigned_attendee_dashboard( $guestDetails, $userId );
	}
}