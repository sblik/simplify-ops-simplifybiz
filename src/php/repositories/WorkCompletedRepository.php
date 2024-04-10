<?php


/**
 * @method static WorkCompletedEntity|null get_one( array $filters )
 * @method static WorkCompletedEntity|null get_one_for_current_user()
 * @method static WorkCompletedEntity|null get_one_by_id( $id )
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

	/**
	 * @param array $employeesWorkSubmissions
	 * @param UpdateHoursWorkedDevEntity $updateDevRate
	 *
	 * @return array
	 */
	public function get_submissions_for_client( array $employeesWorkSubmissions, UpdateHoursWorkedDevEntity $updateDevRate ): array {
		foreach ( $employeesWorkSubmissions as $workSubmission ) {
			$clientEmail     = $updateDevRate->clientEmail;
			$clientWorkIsFor = $workSubmission->clientEmail;

			if ( $clientEmail == $clientWorkIsFor ) {
				$filteredEmployeesWorkSubmissionsEntries[] = $workSubmission;
			}

		}
		BS_Log::info( "FILTERED ARRAY FOR $clientEmail SUBMISSIONS: ", $filteredEmployeesWorkSubmissionsEntries );

		return $filteredEmployeesWorkSubmissionsEntries;
	}

	public function get_work_submission_entities_if_dev_rate_updated( UpdateHoursWorkedDevEntity $updateDevRate, $queryPeriodFrom, $queryPeriodTo ): array {
		$employeesWorkSubmissions = $this->get_for_user_between_dates( $updateDevRate->employeeUserID, $queryPeriodFrom, $queryPeriodTo );

		if ( $updateDevRate->updateForClientYN == 'Yes' ) {
			return $this->get_submissions_for_client( $employeesWorkSubmissions, $updateDevRate );
		}

		return $employeesWorkSubmissions;
	}
}