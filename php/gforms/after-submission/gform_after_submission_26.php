<?php
/**
 * lakjdsklajsld
 */

add_action( 'gform_after_submission_26', 'sb_gform_after_submission_26', 10, 3 );
function sb_gform_after_submission_26( $entry, $form ) {
	$userId = get_current_user_id();

	$exhibitorOnboard           = new ExhibitorOnboardingEntity( $entry );
	$exhibitorInitiateFormEntry = sbFormMethods::get_gform_entry( FormIds::FORM_ID_43_INITITATE_EXHIBITOR_REGISTRATION, $userId, 'created_by' );
	$dashboard                  = DashboardRepository::get_one_for_current_user();

	$step_id = 328; //Notify Exhibitor To Upload Certificate of Insurance
	$api     = new Gravity_Flow_API( FormIds::FORM_ID_34_PORTAL_DASHBOARD );
	$api->send_to_step( $dashboard->formEntry, $step_id );

	if ( $exhibitorOnboard->is_exhibitor_primary_attending() ) {
		$dashboard->set_coupon_code_as_used();

		// TODO: have this dependency injected when this is converted to a use case
		$createMembership = new CreateMembership();
		$createMembership->create_membership_for_attendee_invited( $userId );

		if ( $exhibitorOnboard->isExhibitorPointOfContact == 'Yes' ) {
			$dashboard->isDashboardForPointOfContact = 'Yes';
		}
	}

	update_dashboard_entry( $exhibitorOnboard, $dashboard );

	$exhibitorInitiateFormEntry['22'] = 'Registration Submitted';

	DashboardRepository::update( $dashboard );
	GFAPI::update_entry( $exhibitorInitiateFormEntry );
}

/**
 * @param exhibitorOnboardingEntity $exhibitorOnboard
 * @param dashboardEntity $dashboard
 *
 * @return void
 */
function update_dashboard_entry( ExhibitorOnboardingEntity $exhibitorOnboard, DashboardEntity $dashboard ): void {
	$dashboard->entryId26RegisterExhibitor        = $exhibitorOnboard->id;
	$dashboard->isExhibitorPrimarySigningContract = $exhibitorOnboard->isExhibitorSigningContract;
	$dashboard->contractSignerEmail               = $exhibitorOnboard->contractSignerEmail;
	$dashboard->hasSubmitted                      = 'Yes';
}