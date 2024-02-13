<?php

/**
 * Use case for creating dashboard entries
 */
class CreateDashboard {

	private CreateDashboardAttendeeInvited $createDashboardAttendeeInvited;
	private CreateDashboardAttendeeReassigned $createDashboardAttendeeReassigned;
	private CreateDashboardExhibitorPrimary $createDashboardExhibitorPrimary;
	private CreateDashboardPurchasedTickets $createDashboardPurchaseTickets;

	public function __construct(
		CreateDashboardAttendeeInvited $createDashboardAttendeeInvited,
		CreateDashboardAttendeeReassigned $createDashboardAttendeeReassigned,
		CreateDashboardExhibitorPrimary $createDashboardExhibitorPrimary,
		CreateDashboardPurchasedTickets $createDashboardPurchaseTickets
	) {

		$this->createDashboardAttendeeInvited    = $createDashboardAttendeeInvited;
		$this->createDashboardAttendeeReassigned = $createDashboardAttendeeReassigned;
		$this->createDashboardExhibitorPrimary   = $createDashboardExhibitorPrimary;
		$this->createDashboardPurchaseTickets    = $createDashboardPurchaseTickets;
	}

	/**
	 * @param  ExhibitorInviteEntity  $exhibitorInvite
	 * @param $userId
	 * @param $manageGuestsParentEntryId
	 *
	 * @return void
	 */
	public function create_exhibitor_primary_dashboard( ExhibitorInviteEntity $exhibitorInvite, $userId, $manageGuestsParentEntryId ) {
		$this->createDashboardExhibitorPrimary->create_exhibitor_primary_dashboard( $exhibitorInvite, $userId, $manageGuestsParentEntryId );
	}

	/**
	 * @param  PurchasedTicketEntity  $purchasedTicket
	 * @param $userId
	 *
	 * @return void
	 */
	public function create_invited_attendee_dashboard( PurchasedTicketEntity $purchasedTicket, $userId ) {
		$this->createDashboardAttendeeInvited->create_invited_attendee_dashboard( $purchasedTicket, $userId );
	}

	/**
	 * @param  GuestDetailsEntity  $guestDetails
	 * @param $userId
	 *
	 * @return void
	 */
	public function created_reassigned_attendee_dashboard( GuestDetailsEntity $guestDetails, $userId ) {
		$this->createDashboardAttendeeReassigned->created_reassigned_attendee_dashboard( $guestDetails, $userId );
	}

	/**
	 * Update a dashboard if it exists otherwise create one
	 *
	 * @param $user_id
	 * @param  PurchasedTicketEntity  $purchasedTicket
	 *
	 * @return null|DashboardEntity
	 */
	public function create_purchased_tickets_dashboard( $user_id, PurchasedTicketEntity $purchasedTicket ): DashboardEntity {
		BS_Log::info( "CreateDashboard for purchased tickets", $purchasedTicket );

		$dashboard = DashboardRepository::get_one_for_user( $user_id );

		if ( empty( $dashboard ) ) {
			$dashboard = $this->createDashboardPurchaseTickets->create_purchased_tickets_dashboard( $user_id, $purchasedTicket );
		} else {
			$dashboard = updateDashboard::update_dashboard_entry( $dashboard, $purchasedTicket );
		}

		return $dashboard;
	}
}