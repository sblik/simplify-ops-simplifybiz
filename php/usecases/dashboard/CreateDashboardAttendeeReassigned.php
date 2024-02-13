<?php

/**
 * Use case for a dashboard for a re-assigned attendee
 */
class CreateDashboardAttendeeReassigned {
	/**
	 * @param  GuestDetailsEntity  $guestDetails
	 * @param $userId
	 *
	 * @return void
	 */
	public static function created_reassigned_attendee_dashboard( GuestDetailsEntity $guestDetails, $userId ): void {
		BS_Log::info( "called create_dashboard_entry_attendee_invited_reassigned() -----------------------" );

		$dashboard = new DashboardEntity();

		$dashboard->createdBy        = $userId;
		$dashboard->primaryNameFirst = $guestDetails->legalFirstName;
		$dashboard->primaryNameLast  = $guestDetails->legalLastName;
		$dashboard->primaryEmail     = $guestDetails->email;
		$dashboard->ticketType       = 1;

		DashboardRepository::add( $dashboard );

		BS_Log::info( "create_dashboard_entry_attendee_invited_reassigned COMPLETE -----------------------" );
	}

}