<?php

/**
 * Use case for a dashboard when tickets are purchased
 */
class CreateDashboardPurchasedTickets {
	/**
	 * @param $user_id
	 * @param  PurchasedTicketEntity  $purchasedTicket
	 *
	 * @return null|DashboardEntity
	 */
	public static function create_purchased_tickets_dashboard( $user_id, PurchasedTicketEntity $purchasedTicket ): ?DashboardEntity {

		// TODO: create a type to represent the roles
		if ( $purchasedTicket->role == 'attendee' ) {
			$couponCodeMax = $purchasedTicket->totalTickets - 1;

			$dashboard = new DashboardEntity();

			$dashboard->createdBy          = $user_id;
			$dashboard->entryId5BuyTickets = $purchasedTicket->id;
			$dashboard->isContactPerson    = $purchasedTicket->isTicketForContactPerson;
			$dashboard->storedCouponCode   = $purchasedTicket->generatedCouponCode;
			$dashboard->couponUsed         = 0; //Start count for how many times their coupon has been used
			$dashboard->couponMax          = $couponCodeMax;
			$dashboard->couponBalance      = $couponCodeMax;
			$dashboard->ticketsTotal       = $purchasedTicket->totalTickets;
			$dashboard->primaryNameFirst   = $purchasedTicket->firstName;
			$dashboard->primaryNameLast    = $purchasedTicket->lastName;
			$dashboard->ticketType         = $purchasedTicket->ticketType;
			$dashboard->primaryEmail       = $purchasedTicket->email;
			
			BS_Log::info( 'Dashboard Entry to Create', $dashboard );

			DashboardRepository::add( $dashboard );

			return $dashboard;
		}

		// TODO: what about other role types?
		return null;
	}
}