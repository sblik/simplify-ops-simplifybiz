<?php

/**
 * A repository for performing CRUD operations on the guest details form
 *
 * @method static GuestDetailsEntity|null get_one( $fieldId, $value )
 * @method static GuestDetailsEntity|null get_one_by_id( $value )
 * @method static GuestDetailsEntity[]|WP_Error get_all( $fieldId, $value, string $direction = 'ASC' )
 * @method static int|WP_Error add( GuestDetailsEntity $entity )
 */
class GuestDetailsRepository extends BS_BaseRepository {
	protected static $entityType = GuestDetailsEntity::class;
	protected static int $formId = FormIds::FORM_ID_8_GUEST_DETAILS_REPEATER;

	/**
	 * Get the entries where the contact person id matches the given $contactPersonId
	 *
	 * @return GuestDetailsEntity[]|WP_Error
	 */
	static function get_all_for_contact_person( $contactPersonId ) {
		return self::get_all( GuestDetailsEntity::get_form_id( 'contactPersonId' ), $contactPersonId );
	}
}