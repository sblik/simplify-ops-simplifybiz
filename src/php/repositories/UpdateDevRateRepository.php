<?php

/**
 * @method static UpdateHoursWorkedDevEntity|null get_one( array $filters )
 * @method static UpdateHoursWorkedDevEntity|null get_one_for_current_user()
 * @method static UpdateHoursWorkedDevEntity|null get_one_by_id( $userId )
 * @method static UpdateHoursWorkedDevEntity[] get_all( array $filters = null, string $direction = 'ASC' )
 * @method static UpdateHoursWorkedDevEntity[] get_all_between( DateTime $startDate, DateTime $endDate, array $filters = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( UpdateHoursWorkedDevEntity $entity )
 */
class UpdateDevRateRepository extends SMPLFY_BaseRepository {
	public function __construct( SMPLFY_GravityFormsApiWrapper $gravityFormsApi ) {
		$this->formId     = 161;
		$this->entityType = UpdateHoursWorkedDevEntity::class;
		parent::__construct( $gravityFormsApi );
	}
}