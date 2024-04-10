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

		$employeesWorkSubmissions = $this->workCompletedReportsRepository->get_work_submission_entities_if_dev_rate_updated(
			$updateDevRate->employeeUserID,
			$updateDevRate->queryPeriodFrom,
			$updateDevRate->queryPeriodTo
		);

		$count = count( $employeesWorkSubmissions );
		SMPLFY_Log::info( "Updating $count historical submissions to use rate $updateDevRate->devRate for user $userID between $updateDevRate->queryPeriodFrom and $updateDevRate->queryPeriodTo" );

		if ( $updateDevRate->updateDevRateMetaYN == 'Yes' ) {
			update_user_meta( $userID, 'devrate', $newDevRate );
		}

		foreach ( $employeesWorkSubmissions as $employeeWorkSubmission ) {
			$employeeWorkSubmission->hoursSpent = $employeeWorkSubmission->numberOfHoursWorked * $newDevRate;
			$employeeWorkSubmission->devRate    = $newDevRate;

			$this->workCompletedReportsRepository->update( $employeeWorkSubmission );
		}
	}
}