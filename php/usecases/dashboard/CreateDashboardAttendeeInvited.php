<?php

/**
 * Use case for a dashboard for an invited attendee
 */
class CreateDashboardAttendeeInvited {

	/**
	 * @param  PurchasedTicketEntity  $purchasedTicket
	 * @param $user_id
	 *
	 * @return void
	 */
	public static function create_invited_attendee_dashboard( PurchasedTicketEntity $purchasedTicket, $user_id ): void {
		BS_Log::info( "called create_dashboard_entry_attendee_invited() -----------------------" );

		$dashboard = new DashboardEntity();

		$dashboard->createdBy          = $user_id;
		$dashboard->entryId5BuyTickets = $purchasedTicket->id;
		$dashboard->primaryNameFirst   = $purchasedTicket->firstName;
		$dashboard->primaryNameLast    = $purchasedTicket->lastName;
		$dashboard->primaryEmail       = $purchasedTicket->email;
		$dashboard->ticketType         = $purchasedTicket->ticketType;

		DashboardRepository::add( $dashboard );
		BS_Log::info( "create_dashboard_entry_attendee_invited COMPLETE -----------------------" );
	}
}