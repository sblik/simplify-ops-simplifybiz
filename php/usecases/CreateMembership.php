<?php

/**
 * Use case for managing memberships
 */
class CreateMembership {

	/**
	 * Create a membership for a primary attendee
	 *
	 * @param $user_id
	 *
	 * @return void
	 */
	function create_membership_for_attendee_primary( $user_id ): void {
		BS_Log::info( "Create membership Attendee Primary $user_id" );
		self::create( MembershipType::CONTACT_PERSON, $user_id );
		self::create( MembershipType::ATTENDEE, $user_id );
	}

	/**
	 * Create a new membership in member press
	 *
	 * @param $productId
	 * @param $user_id
	 *
	 * @return void
	 */
	static function create( $productId, $user_id ) {
		MemberPressAdapter::create_membership_transaction( $productId, $user_id );
	}

	/**
	 * Create a membership for an invited attendee
	 *
	 * @param $user_id
	 *
	 * @return void
	 */
	function create_membership_for_attendee_invited( $user_id ) {
		BS_Log::info( "Create membership Attendee Invited $user_id" );
		self::create( MembershipType::ATTENDEE, $user_id );
	}

	/**
	 * Create a membership for a speaker
	 *
	 * @param $user_id
	 *
	 * @return void
	 */
	function create_membership_for_speaker( $user_id ) {
		BS_Log::info( "Create membership Attendee Speaker $user_id" );
		self::create( MembershipType::ATTENDEE, $user_id );
		self::create( MembershipType::SPEAKER, $user_id );
	}

	/**
	 * Create membership for an exhibitor primary
	 *
	 * @param $user_id
	 *
	 * @return void
	 */
	function create_membership_for_exhibitor_primary( $user_id ): void {
		BS_Log::info( "Create membership Attendee Primary, $user_id" );
		self::create( MembershipType::CONTACT_PERSON, $user_id );
		self::create( MembershipType::EXHIBITOR, $user_id );
	}
}