<?php

/**
 * Use case for a dashboard for an exhibitor primary
 */
class CreateDashboardExhibitorPrimary {
	/**
	 * @param ExhibitorInviteEntity $exhibitorInvite
	 * @param $user_id
	 * @param $manageGuestsParentEntryId
	 *
	 * @return void
	 */
	public static function create_exhibitor_primary_dashboard( ExhibitorInviteEntity $exhibitorInvite, $user_id, $manageGuestsParentEntryId ): void {
		BS_Log::info( "called create_dashboard_entry_exhibitor_primary() -----------------------" );

		[ $numberOfTickets, $packageTypeName ] = self::calculate_exhibitor_number_of_tickets(
			$exhibitorInvite->quantity,
			$exhibitorInvite->packageTypeId
		);

		$numberOfCouponUses = 0;
		$dashboard          = new DashboardEntity();

		$dashboard->createdBy                              = $user_id;
		$dashboard->entryId43InitiateExhibitorRegistration = $exhibitorInvite->id;
		$dashboard->isContactPerson                        = 'Yes';
		$dashboard->exhibitorName                          = $exhibitorInvite->organizationName;
		$dashboard->storedCouponCode                       = $exhibitorInvite->couponCode;
		$dashboard->couponUsed                             = $numberOfCouponUses;
		$dashboard->couponMax                              = $numberOfTickets;
		$dashboard->couponBalance                          = $numberOfTickets;
		$dashboard->ticketsTotal                           = $numberOfTickets;
		$dashboard->primaryNameFirst                       = $exhibitorInvite->firstName;
		$dashboard->primaryNameLast                        = $exhibitorInvite->lastName;
		$dashboard->primaryEmail                           = $exhibitorInvite->email;
		$dashboard->phone                                  = $exhibitorInvite->phone;
		$dashboard->exhibitorPackage                       = $packageTypeName;
		$dashboard->entryId23ManageGuests                  = $manageGuestsParentEntryId;

		BS_Log::info( 'Dashboard Entry to Create', $dashboard );

		DashboardRepository::add( $dashboard );

		BS_Log::info( "update_dashboard_entry_attendee_primary COMPLETE -----------------------" );
	}

	/**
	 * Calculate the number of tickets that exhibitor will get based on the package type
	 *
	 * @param $quantity
	 * @param $packageTypeId
	 *
	 * @return array
	 */
	static function calculate_exhibitor_number_of_tickets( $quantity, $packageTypeId ): array {
		switch ( $packageTypeId ) {
			case 1:
				return [ intval( $quantity ) * ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_PREMIUM, 'Title' ];
			case 2:
				return [ intval( $quantity ) * ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_PLATINUM, 'Platinum' ];
			case 3:
				return [ intval( $quantity ) * ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_DIAMOND, 'Diamond' ];
			case 4:
				return [ intval( $quantity ) * ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_GOLD, 'Gold' ];
			case 5:
				return [ intval( $quantity ) * ExhibitorPackageAttendeeQuantities::ATTENDEE_QTY_SILVER, 'Silver' ];
			default:
				return [ 0, 'Unknown' ];
		}
	}
}