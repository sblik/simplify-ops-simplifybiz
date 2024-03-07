<?php


/**
 * A repository for performing CRUD operations on the attendee form
 *
 * @method static WorkCompletedReportEntity|null get_one( $fieldId, $value )
 * @method static WorkCompletedReportEntity|null get_one_for_current_user()
 * @method static WorkCompletedReportEntity|null get_one_by_id( $userId )
 * @method static WorkCompletedReportEntity[] get_all( $fieldId = null, $value = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( WorkCompletedReportEntity $entity )
 */
class WorkCompletedReportsRepository extends BS_BaseRepository {
	protected static $entityType = WorkCompletedReportEntity::class;
	protected static int $formId = 50;
}