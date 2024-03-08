<?php

add_action( 'gform_after_submission_161', 'update_hours_worked_dev_rate', 10, 2 );
function update_hours_worked_dev_rate( $entry, $form ) {

	$updateDevRate            = new UpdateHoursWorkedDevEntity( $entry );
	$newDevRate               = $updateDevRate->devRate;
	$userID                   = $updateDevRate->employeeUserID;
	$employeesWorkSubmissions = get_work_submission_entities( $updateDevRate );

	if ( $updateDevRate->updateDevRateMetaYN == 'Yes' ) {
		update_user_meta( $userID, 'devrate', $newDevRate );
	}

	foreach ( $employeesWorkSubmissions as $employeeWorkSubmission ) {
		$employeeWorkSubmission->consumedHours = $employeeWorkSubmission->numberOfHoursWorked * $newDevRate;
		$employeeWorkSubmission->devRate       = $newDevRate;

		WorkCompletedReportsRepository::update( $employeeWorkSubmission );
	}

}

/**
 * @param UpdateHoursWorkedDevEntity $updateDevRate
 *
 * @return WorkCompletedReportEntity[]
 */
function get_work_submission_entities( UpdateHoursWorkedDevEntity $updateDevRate ): array {
	$employeesWorkSubmissionsEntries = SbFormMethods::get_entries_between_date_for_user( 50, $updateDevRate->employeeUserID, $updateDevRate->queryPeriodFrom, $updateDevRate->queryPeriodTo );
	foreach ( $employeesWorkSubmissionsEntries as $employeeEntry ) {
		$employeesWorkSubmissions[] = new WorkCompletedReportEntity( $employeeEntry );
	}
	if ( $updateDevRate->updateForClientYN == 'Yes' ) {
		return get_submissions_for_client( $employeesWorkSubmissions, $updateDevRate );
	}

	return $employeesWorkSubmissions;
}

/**
 * @param array $employeesWorkSubmissions
 * @param UpdateHoursWorkedDevEntity $updateDevRate
 *
 * @return array
 */
function get_submissions_for_client( array $employeesWorkSubmissions, UpdateHoursWorkedDevEntity $updateDevRate ): array {
	foreach ( $employeesWorkSubmissions as $workSumbission ) {
		$clientEmail     = $updateDevRate->clientEmail;
		$clientWorkIsFor = $workSumbission->clientEmail;

		if ( $clientEmail == $clientWorkIsFor ) {
			$filteredEmployeesWorkSubmissionsEntries[] = $workSumbission;
		}

	}
	BS_Log::info( "FILTERED ARRAY FOR $clientEmail SUBMISSIONS: ", $filteredEmployeesWorkSubmissionsEntries );

	return $filteredEmployeesWorkSubmissionsEntries;
}