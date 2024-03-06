<?php

namespace repositories;

use UpdateHoursWorkedDevEntity;
use BS_BaseRepository;
use WP_Error;

/**
 * A repository for performing CRUD operations on the attendee form
 *
 * @method static UpdateHoursWorkedDevEntity|null get_one( $fieldId, $value )
 * @method static UpdateHoursWorkedDevEntity|null get_one_for_current_user()
 * @method static UpdateHoursWorkedDevEntity|null get_one_by_id( $userId )
 * @method static UpdateHoursWorkedDevEntity[] get_all( $fieldId = null, $value = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( UpdateHoursWorkedDevEntity $entity )
 */
class UpdateDevRateRepository extends BS_BaseRepository {
	protected static $entityType = UpdateHoursWorkedDevEntity::class;
	protected static int $formId = 161;
}