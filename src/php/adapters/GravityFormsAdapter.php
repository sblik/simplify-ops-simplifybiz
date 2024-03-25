<?php

/**
 * Adapter for handling Gravity Forms events
 */
class GravityFormsAdapter {

	private UpdateHoursWorked $updateHoursWorked;
	private AddUserContactMethod $addUserContactMethod;

	public function __construct( UpdateHoursWorked $updateHoursWorked ) {
		$this->updateHoursWorked    = $updateHoursWorked;
		$this->addUserContactMethod = new AddUserContactMethod();

		$this->register_hooks();
		$this->register_filters();
	}

	/**
	 * Register gravity forms hooks to handle custom logic
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'gform_after_submission_161', [ $this->updateHoursWorked, 'update_dev_rate' ], 10, 2 );
	}

	/**
	 * Register gravity forms filters to handle custom logic
	 *
	 * @return void
	 */
	public function register_filters() {
		add_filter( 'user_contactmethods', [ $this->addUserContactMethod, 'add_organization' ], 10, 2 );

	}
}