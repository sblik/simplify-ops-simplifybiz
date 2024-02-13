<?php

/**
 * A repository for performing CRUD operations on the manage guests form
 *
 * @method static ManageGuestsEntity|null get_one( $fieldId, $value )
 * @method static ManageGuestsEntity[]|WP_Error get_all( $fieldId, $value, string $direction = 'ASC' )
 * @method static int|WP_Error add( ManageGuestsEntity $entity )
 */
class ManageGuestsRepository extends BS_BaseRepository {
	protected static $entityType = ManageGuestsEntity::class;
	protected static int $formId = FormIds::FORM_ID_23_MANAGE_GUESTS;

	/**
	 * Create a manage guests entry for a user
	 *
	 * @param $user_id
	 *
	 * @return int|WP_Error
	 */
	static function create( $user_id ) {
		$entity            = new ManageGuestsEntity();
		$entity->createdBy = $user_id;

		return self::add( $entity );
	}
}