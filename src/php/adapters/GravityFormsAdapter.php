<?php

/**
 * Adapter for handling Gravity Forms events
 */
class GravityFormsAdapter {

	private UpdateHoursWorked $updateHoursWorked;

	public function __construct( UpdateHoursWorked $updateHoursWorked ) {
		$this->updateHoursWorked = $updateHoursWorked;

		$this->register_hooks();
	}

	/**
	 * Register gravity forms hooks to handle custom logic
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'gform_after_submission_161', [ $this->updateHoursWorked, 'update_dev_rate' ], 10, 2 );
	}
}