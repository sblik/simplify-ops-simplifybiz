<?php

/**
 * A use case for handling user registration for registered exhibitor
 */
class UserRegisteredExhibitor {

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
	 * Handle exhibitor registration
	 *
	 * @param  ExhibitorInviteEntity  $exhibitorInvite
	 * @param $userId
	 *
	 * @return void
	 */
	function handle( ExhibitorInviteEntity $exhibitorInvite, $userId ): void {
		BS_Log::info( "UserRegisteredExhibitor", $exhibitorInvite );

		$this->createMembership->create_membership_for_exhibitor_primary( $userId );
		$manageGuestsParentEntryId = ManageGuestsRepository::create( $userId );

		$this->createDashboard->create_exhibitor_primary_dashboard( $exhibitorInvite, $userId, $manageGuestsParentEntryId );
	}
}