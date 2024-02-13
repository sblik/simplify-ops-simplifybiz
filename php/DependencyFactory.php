<?php

/**
 * A factory class responsible for creating and initializing all dependencies used in the plugin
 */
class DependencyFactory {

	/**
	 * Create and initialize all dependencies
	 *
	 * @return void
	 */
	static function create_plugin_dependencies() {
		$createMembership = new CreateMembership();

		$createDashboard = new CreateDashboard(
			new CreateDashboardAttendeeInvited(),
			new CreateDashboardAttendeeReassigned(),
			new CreateDashboardExhibitorPrimary(),
			new CreateDashboardPurchasedTickets()
		);

		$userRegisteredBuyTickets = new UserRegisteredBuyTickets(
			new UserRegisteredAttendeeInvited( $createMembership, $createDashboard ),
			new UserRegisteredPrimaryAttendee( $createMembership, $createDashboard ),
			new UserRegisteredSpeaker( $createMembership, $createDashboard ),
		);

		$userRegistered = new UserRegistered(
			$userRegisteredBuyTickets,
			new UserRegisteredExhibitor( $createMembership, $createDashboard ),
			new UserRegisteredReassignedAttendee( $createMembership, $createDashboard )
		);

		$reassignTicket = new ReassignTicket();

		new GravityFormsAdapter( $userRegistered );
		new GravityViewAdapter( $reassignTicket );

		CRYO_Api::init( CRYO_Api_Gateway_Factory::create() );
	}
}