<?php

/**
 * A use case for handling speaker registration
 */
class UserRegisteredSpeaker {

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
	 * Handle speaker attendee registration
	 *
	 * @param $user_id
	 * @param PurchasedTicketEntity $purchasedTicket
	 *
	 * @return void
	 */
	public function handle( $user_id, PurchasedTicketEntity $purchasedTicket ): void {
		$this->createMembership->create_membership_for_speaker( $user_id );
		$this->createDashboard->create_purchased_tickets_dashboard( $user_id, $purchasedTicket );
	}

}