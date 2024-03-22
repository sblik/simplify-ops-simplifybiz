<?php


/**
 * @method static WorkCompletedReportEntity|null get_one( array $filters )
 * @method static WorkCompletedReportEntity|null get_one_for_current_user()
 * @method static WorkCompletedReportEntity|null get_one_by_id( $userId )
 * @method static WorkCompletedReportEntity[] get_all( array $filters = null, string $direction = 'ASC' )
 * @method static WorkCompletedReportEntity[] get_all_between( DateTime $startDate, DateTime $endDate, array $filters = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( WorkCompletedReportEntity $entity )
 */
class WorkCompletedReportsRepository extends SMPLFY_BaseRepository {
	public function __construct( SMPLFY_GravityFormsApiWrapper $gravityFormsApi ) {
		$this->formId     = 50;
		$this->entityType = WorkCompletedReportEntity::class;
		parent::__construct( $gravityFormsApi );
	}

	/**
	 * @param $userId
	 * @param $startDate
	 * @param $endDate
	 *
	 * @return UpdateHoursWorkedDevEntity[]
	 */
	public function get_for_user_between_dates( $userId, $startDate, $endDate ): array {
		return $this->get_all_between( $startDate, $endDate, [ 'created_by' => $userId, ] );
	}
}