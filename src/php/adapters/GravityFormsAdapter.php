<?php

/**
 * Adapter for handling Gravity Forms events
 */
class GravityFormsAdapter {

	private UpdateHoursWorked $updateHoursWorked;
	private WorkReportSubmitted $workReportSubmitted;
	private RecalculateClientBalance $recalculateClientBalance;

	public function __construct( UpdateHoursWorked $updateHoursWorked, WorkReportSubmitted $workReportSubmitted, RecalculateClientBalance $recalculateClientBalance ) {
		$this->updateHoursWorked        = $updateHoursWorked;
		$this->workReportSubmitted      = $workReportSubmitted;
		$this->recalculateClientBalance = $recalculateClientBalance;

		$this->register_hooks();
	}

	/**
	 * Register gravity forms hooks to handle custom logic
	 *
	 * @return void
	 */
	public function register_hooks() {

		add_action( 'gform_after_submission_161', [ $this->updateHoursWorked, 'update_dev_rate' ], 10, 2 );
		add_action( 'gform_after_submission_50', [ $this->workReportSubmitted, 'handle' ], 10, 2 );
		add_action( 'gform_after_submission_163', [ $this->recalculateClientBalance, 'handle' ], 10, 2 );
	}
}