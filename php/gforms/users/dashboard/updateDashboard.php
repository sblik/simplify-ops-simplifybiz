<?php

// TODO: convert to a use case
class updateDashboard {
	/**
	 * @param  DashboardEntity  $existingDashboard
	 * @param  PurchasedTicketEntity  $purchasedTicket
	 *
	 * @return DashboardEntity
	 */
	public static function update_dashboard_entry( DashboardEntity $existingDashboard, PurchasedTicketEntity $purchasedTicket ): DashboardEntity {
		BS_Log::warn( "primary attendee update dashboard called, but has not yet been implemented", $purchasedTicket );

		// TODO: implement update_entry() function
		return $existingDashboard;
	}

	public static function update_dashboard_entry_attendee_primary_with_invited_guest( PurchasedTicketEntity $purchasedTicket ) {
		$dashboard = DashboardRepository::get_by_stored_coupon_code( $purchasedTicket->couponCodeUsed );

		if ( empty( $dashboard ) ) {
			BS_Log::warn( "Attempted to update dashboard but no dashboard found for coupon code: $purchasedTicket->couponCodeUsed" );

			return;
		}

		$dashboard->set_coupon_code_as_used();
		$dashboard->invitedGuestRedeemCount = $dashboard->invitedGuestRedeemCount + 1;

		DashboardRepository::update( $dashboard );

		$manageGuestsEntry = SbFormMethods::get_gform_entry( FormIds::FORM_ID_23_MANAGE_GUESTS, $dashboard->createdBy, 'created_by' );

		// TODO: inject an instance of CreateGuestDetails this updateDashboard converted to a use case
		$createGuestDetails = new CreateGuestDetails();
		$createGuestDetails->create_guest_details( $purchasedTicket, $manageGuestsEntry );
	}
}