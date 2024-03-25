<?php

/**
 * @method static WorkCompletedEntity|null get_one( array $filters )
 * @method static WorkCompletedEntity|null get_one_for_current_user()
 * @method static WorkCompletedEntity|null get_one_by_id( $userId )
 * @method static WorkCompletedEntity[] get_all( array $filters = null, string $direction = 'ASC' )
 * @method static WorkCompletedEntity[] get_all_between( DateTime $startDate, DateTime $endDate, array $filters = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( WorkCompletedEntity $entity )
 */
class WorkCompletedRepository extends SMPLFY_BaseRepository {
	public function __construct( SMPLFY_GravityFormsApiWrapper $gravityFormsApi ) {
		$this->formId     = 50;
		$this->entityType = WorkCompletedEntity::class;
		parent::__construct( $gravityFormsApi );
	}
}