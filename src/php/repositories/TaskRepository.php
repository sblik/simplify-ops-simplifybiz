<?php

/**
 * @method static TaskEntity|null get_one( array $filters )
 * @method static TaskEntity|null get_one_for_current_user()
 * @method static TaskEntity|null get_one_by_id( $id )
 * @method static TaskEntity[] get_all( array $filters = null, string $direction = 'ASC' )
 * @method static TaskEntity[] get_all_between( DateTime $startDate, DateTime $endDate, array $filters = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( TaskEntity $entity )
 */
class TaskRepository extends SMPLFY_BaseRepository {
    public function __construct( SMPLFY_GravityFormsApiWrapper $gravityFormsApi ) {
        $this->formId     = FormIDs::TASKS;
        $this->entityType = TaskEntity::class;
        parent::__construct( $gravityFormsApi );
    }
}