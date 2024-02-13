<?php
/**
 * Whenever a user logs in this function will determine where to redirect them to
 *
 * @param $attendee_redirect_url
 * @param $user
 *
 * @return string
 */
function redirect_user_on_login( $attendee_redirect_url, $user ): string {
	$userId = $user->data->ID;

	BS_Log::info( "Redirecting user $userId to correct page on login" );

	if ( does_user_have_role( $user, 'sales_rep' ) ) {
		BS_Log::info( "User $userId is a sales rep, redirecting to sales dashboard..." );

		return '/sales-dashboard/';
	}

	if ( does_user_have_role( $user, 'sales_manager' ) ) {
		BS_Log::info( "User $userId is a sales manager, redirecting to manager dashboard..." );

		return '/manager-dashboard/';
	}

	$membershipsForUser = MeprTransaction::get_all_by_user_id( $userId );
	$isExhibitor        = does_user_have_membership( MembershipType::EXHIBITOR, $membershipsForUser );
	$isAttendee         = does_user_have_membership( MembershipType::ATTENDEE, $membershipsForUser );
	$isSpeaker          = does_user_have_membership( MembershipType::SPEAKER, $membershipsForUser );

	BS_Log::info( "User $userId memberships: isExhibitor - $isExhibitor, isAttendee - $isAttendee, isSpeaker - $isSpeaker" );

	$dashboard = DashboardRepository::get_one_for_user( $userId );

	BS_Log::info( "User $userId dashboard:", $dashboard );

	return determine_redirect_url( $dashboard, $isExhibitor, $isAttendee, $isSpeaker );
}

/**
 * @param  DashboardEntity|null  $dashboard
 * @param  bool  $isExhibitor
 * @param  bool  $isAttendee
 * @param  bool  $isSpeaker
 *
 * @return string
 */
function determine_redirect_url( ?DashboardEntity $dashboard, bool $isExhibitor, bool $isAttendee, bool $isSpeaker ): string {
	if ( empty( $dashboard ) ) {
		return SITE_URL;
	}
	
	$exhibitorRegistrationCompleted = $dashboard->entryId26RegisterExhibitor;
	$speakerRegistrationCompleted   = $dashboard->entryId38RegisterSpeaker;
	$attendeeRegistrationCompleted  = $dashboard->entryId37RegisterAttendee;

	$exhibitorRegistrationUrl = SITE_URL . '/do-items/?id=' . FormIds::FORM_ID_26_REGISTER_AS_AN_EXHIBITOR;
	$attendeeRegistrationUrl  = SITE_URL . '/do-items/?id=' . FormIds::FORM_ID_37_REGISTER_AS_AN_ATTENDEE;
	$speakerRegistrationUrl   = SITE_URL . '/do-items/?id=' . FormIds::FORM_ID_38_REGISTER_AS_A_SPEAKER;
	$dashboardUrl             = SITE_URL . '/view/dashboard/entry/' . $dashboard->id;

	if ( $isExhibitor && ! $exhibitorRegistrationCompleted ) {
		BS_Log::info( "Exhibitor registration not completed, redirecting to exhibitor registration..." );

		return $exhibitorRegistrationUrl;
	}

	if ( $isAttendee && ! $attendeeRegistrationCompleted ) {
		BS_Log::info( "Attendee registration not completed, redirecting to exhibitor registration..." );

		return $attendeeRegistrationUrl;
	}

	if ( $isSpeaker && ! $speakerRegistrationCompleted ) {
		BS_Log::info( "Speaker registration not completed, redirecting to exhibitor registration..." );

		return $speakerRegistrationUrl;
	}

	BS_Log::info( "Registration is complete, redirecting to dashboard..." );

	return $dashboardUrl;
}

function does_user_have_membership( $membershipType, $membershipsForUser ): bool {
	$matchingMemberships = array_filter( $membershipsForUser, function ( $membership ) use ( $membershipType ) {
		return $membership->product_id == $membershipType;
	} );

	return count( $matchingMemberships ) > 0;
}

function does_user_have_role( $user, $roleName ): bool {
	foreach ( $user->caps as $role => $true ) {
		if ( $role == $roleName ) {
			return true;
		}
	}

	return false;
}


add_filter( 'mepr-process-login-redirect-url', 'redirect_user_on_login', 11, 2 );