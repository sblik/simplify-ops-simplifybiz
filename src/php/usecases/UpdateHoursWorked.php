<?php

/**
 * Manually adjust the work hours for a user when on submitting form 161
 */

class UpdateHoursWorked {
	private WorkCompletedRepository $workCompletedReportsRepository;

	public function __construct( WorkCompletedRepository $workCompletedReportsRepository ) {
		$this->workCompletedReportsRepository = $workCompletedReportsRepository;
	}

	/**
	 * @param $entry
	 *
	 * @return void
	 */
	function update_dev_rate( $entry ) {
		$updateDevRate = new UpdateHoursWorkedDevEntity( $entry );
		$newDevRate    = $updateDevRate->devRate;
		$userID        = $updateDevRate->employeeUserID;

		SMPLFY_Log::info( "Update dev rate", $updateDevRate );

		$employeesWorkSubmissions = $this->get_work_completed_entities( $updateDevRate, $this->workCompletedReportsRepository );

		$count = count( $employeesWorkSubmissions );
		SMPLFY_Log::info( "Updating $count historical submissions to use rate $updateDevRate->devRate for user $userID between $updateDevRate->queryPeriodFrom and $updateDevRate->queryPeriodTo" );

		if ( $updateDevRate->updateForFutureSubmissionsYN == 'Yes' ) {
			update_user_meta( $userID, 'devrate', $newDevRate );
		}

		foreach ( $employeesWorkSubmissions as $employeeWorkSubmission ) {
			$employeeWorkSubmission->hoursSpent = $employeeWorkSubmission->numberOfHoursWorked * $newDevRate;
			$employeeWorkSubmission->devRate    = $newDevRate;

			$this->workCompletedReportsRepository->update( $employeeWorkSubmission );
		}
	}

	/**
	 * @param  UpdateHoursWorkedDevEntity  $updateDevRate
	 * @param  WorkCompletedRepository  $workCompletedRepository
	 *
	 * @return UpdateHoursWorkedDevEntity[]|WorkCompletedEntity[]
	 */
	function get_work_completed_entities( UpdateHoursWorkedDevEntity $updateDevRate, WorkCompletedRepository $workCompletedRepository ): array {
		$employeeUserID    = $updateDevRate->employeeUserID;
		$queryPeriodFrom   = $updateDevRate->queryPeriodFrom;
		$queryPeriodTo     = $updateDevRate->queryPeriodTo;
		$updateForClientYN = $updateDevRate->updateForClientYN;

		if ( $updateForClientYN == 'Yes' ) {
			return $workCompletedRepository->get_for_user_and_client_between_dates( $employeeUserID, $queryPeriodFrom, $queryPeriodTo, $updateDevRate->clientEmail );
		}

		return $workCompletedRepository->get_for_user_between_dates( $employeeUserID, $queryPeriodFrom, $queryPeriodTo );
	}
}