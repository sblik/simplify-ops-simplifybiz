<?php

class UpdateHoursWorked {
	private WorkCompletedReportsRepository $workCompletedReportsRepository;

	public function __construct( WorkCompletedReportsRepository $workCompletedReportsRepository ) {
		$this->workCompletedReportsRepository = $workCompletedReportsRepository;
	}

	function update_dev_rate( $entry, $form ) {
		$updateDevRate = new UpdateHoursWorkedDevEntity( $entry );
		$newDevRate    = $updateDevRate->devRate;
		$userID        = $updateDevRate->employeeUserID;

		SMPLFY_Log::info( "Update dev rate", $updateDevRate );

		$employeesWorkSubmissions = $this->get_work_submission_entities( $updateDevRate );

		if ( $updateDevRate->updateDevRateMetaYN == 'Yes' ) {
			update_user_meta( $userID, 'devrate', $newDevRate );
		}

		foreach ( $employeesWorkSubmissions as $employeeWorkSubmission ) {
			$employeeWorkSubmission->consumedHours = $employeeWorkSubmission->numberOfHoursWorked * $newDevRate;
			$employeeWorkSubmission->devRate       = $newDevRate;

			$this->workCompletedReportsRepository->update( $employeeWorkSubmission );
		}

	}

	/**
	 * @param  UpdateHoursWorkedDevEntity  $updateDevRate
	 *
	 * @return WorkCompletedReportEntity[]
	 */
	private function get_work_submission_entities( UpdateHoursWorkedDevEntity $updateDevRate ): array {
		// TODO: move this to repository
		$employeesWorkSubmissionsEntries = SbFormMethods::get_entries_between_date_for_user( 50, $updateDevRate->employeeUserID, $updateDevRate->queryPeriodFrom, $updateDevRate->queryPeriodTo );
		$employeesWorkSubmissions        = array();

		foreach ( $employeesWorkSubmissionsEntries as $employeeEntry ) {
			$employeesWorkSubmissions[] = new WorkCompletedReportEntity( $employeeEntry );
		}

		return $employeesWorkSubmissions;
	}
}